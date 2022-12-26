<?php


namespace ModulesGarden\WordpressManager\App\Cron;

use Exception;
use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Services\CronService;
use ModulesGarden\WordpressManager\Core\CommandLine\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class SynchronizeTask extends Command
{
    use main\App\Http\Admin\BaseAdminController;

    protected $name = 'SynchronizeTask';

    /**
     * Command description
     * @var string
     */
    protected $description = '';

    /**
     * Command help text
     * @var string
     */
    protected $help = '';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param SymfonyStyle $io
     */
    protected $input;
    protected $output;
    protected $io;


    protected const WP_APP_ID = 26;

    protected $synchronizedServices = 0;


    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $this->setInterfaces($input, $output, $io);

        $this->synchronizeTasks();

        $io->success([
            sprintf("Synchronize tasks: %s Entries Processed.", $this->synchronizedServices),
            "Synchronize tasks: Done"
        ]);

        $this->clean();
    }

    protected function synchronizeTasks()
    {
        foreach (CronService::getProductsEnabled() as $product)
        {
            $this->synchronizeProductServices($product->id);
        }
    }


    protected function synchronizeProductServices($productId)
    {
        $query = Hosting::where('packageid', $productId)
            ->select("id")
            ->where('domainstatus', 'Active');

        foreach ($query->get() as $hosting)
        {
            $this->synchronizeService($hosting->id);
        }
    }


    protected function synchronizeService($serviceId)
    {
        $this->notify(["Synchronize Hosting: %s" => $serviceId]);
        $this->reset();
        try
        {
            $this->setHostingId($serviceId);
            //Is reseller account
            if ($this->getHosting()->product->isTypeReseller())
            {
                $this->resellerProcess($this->input, $this->output, $this->io);
            }
            else
            {
                $this->hostingProcess();
            }

            $this->synchronizedServices++;

            $this->notify(["Hosting: %s has been synchronized" => $serviceId]);
        }
        catch (Exception $ex)
        {
            $this->io->error($ex->getMessage());
        }
    }


    private function resellerProcess(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $relationIds = [];
        $appIds      = (array)$this->getHosting()->productSettings['installationScripts'];
        if (empty($appIds))
        {
            $appIds = [self::WP_APP_ID];
        }

        foreach ($this->subModule()->reseller()->getAccounts() as $data)
        {
            try
            {
                $this->subModule()->setUsername($data['username']);
                $relationIds = $this->loopInstallations($data, $appIds);
            }
            catch (Exception $ex)
            {
                $io->error($ex->getMessage());
            }
        }

        $this->deleteInstallation($this->getHosting()->id, $relationIds);
    }

    private function hostingProcess()
    {
        $relationIds = [];
        $appIds      = array_keys($this->getHosting()->productSettings->getSettings()['installationScripts']);
        $relationIds = $this->loopInstallations([], $appIds);
        $this->deleteInstallation($this->getHosting()->id, $relationIds);
    }

    protected function loopInstallations($data, $appIds)
    {
        foreach ($this->subModule()->getInstallations($appIds) as $installation)
        {
            //Live installation does not exist
            if ($installation['staging'] && !Installation::where('hosting_id', $this->getHosting()->id)->where('relation_id', $installation['staging'])->count())
            {
                continue;
            }

            $repository = new main\App\Repositories\InstallationRepository();
            $model      = $repository->forHostingAndRelation($this->getHosting(), $installation['id']);

            $this->createOrUpdate($model, $installation, $data);

            //Related Id
            $relationIds[] = $installation['id'];

            return $relationIds;
        }
    }

    private function createOrUpdate($model, $installation, $data)
    {
        if (!$model->domain && !$model->url && !$model->path)
        {
            $model->domain = !empty($installation['domain']) ? $installation['domain'] : parse_url($installation['url'], PHP_URL_HOST);
            $model->url    = $installation['url'];
            $model->path   = $installation['path'];
        }
        if ($model->url && $model->path && ($model->url != $installation['url'] || $model->path != $installation['path']))
        {
            $model->url  = $installation['url'];
            $model->path = $installation['path'];
        }

        $model->staging = $installation['staging'];
        $model->version = $installation['version'];
        $this->setUsername($data, $model);
        $this->createEvent($model);
        $model->save();
    }

    private function createEvent($model)
    {
        $isNew = !$model->id;

        if ($isNew)
        {
            main\Core\Helper\fire(new InstallationCreatedEvent($model));
        }
    }

    private function setUsername($data, $model)
    {
        if ($data['username'])
        {
            $model->username = $data['username'];
        }
    }

    protected function deleteInstallation($id, $relationIds)
    {
        if ($relationIds)
        {
            return Installation::ofHostingId($id)
                ->ofRelationNotIn($relationIds)
                ->delete();
        }
    }

    private function notify($messages)
    {
        foreach ($messages as $message => $extraValue)
        {
            $this->output->writeln(sprintf(" $message ", $extraValue));
        }
    }

    private function clean()
    {
        Installation::whereNotIn('hosting_id', function ($query) {
            $query->select('id')
                ->from((new \ModulesGarden\WordpressManager\Core\Models\Whmcs\Hosting())->getTable())
                ->whereIn("domainstatus", ["Active", "Suspended"]);
        })->delete();
    }

    private function setInterfaces($input, $output, $io)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->io     = $io;
    }
}
