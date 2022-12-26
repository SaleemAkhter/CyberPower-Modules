<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 30, 2018)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Cron;

use DateTime;
use Exception;
use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\PluginBlocked as PluginBlockedModel;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Services\CronService;
use ModulesGarden\WordpressManager\Core\CommandLine\Command;
use ModulesGarden\WordpressManager\Core\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of PluginBlocked
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlocked extends Command
{

    use main\App\Http\Admin\BaseAdminController;

    /**
     * Command name
     * @var string
     */
    protected $name = 'pluginblocked';

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
    protected  $input;
    protected  $output;
    protected  $io;

    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $io->title('Delete Blocked Plugins: Running');
        $this->setInterfaces($input, $output, $io);
        //Get products
        $synchronizedTasks = 0;
        foreach (CronService::getProductsEnabled() as $product)
        {
           $synchronizedTasks += $this->loopHostings($product);
        }
        $output->writeln("");
        $io->success([
            sprintf("Deleted Blocked Plugins: %s Entries Processed.", $synchronizedTasks),
            "Delete Blocked Plugins: Done"
        ]);
        if ($this->hosting)
        {
            $settings                         = $this->hosting->productSettings->getSettings();
            $settings['pluginCrobJobLastRun'] = date("Y-m-d H:i:s");
            $this->hosting->productSettings->setSettings($settings)->save();
        }
    }

    private function scanInteral()
    {
        if (!$this->hosting->productSettings->getScanInteral())
        {
            return true;
        }
        if (!$this->hosting->productSettings->getPluginCrobJobLastRun())
        {
            return true;
        }
        $lastRun = new DateTime($this->hosting->productSettings->getPluginCrobJobLastRun());
        $dDiff   = $lastRun->diff(new DateTime());
        return $dDiff->h >= $this->hosting->productSettings->getScanInteral();
    }

    private function installationProcess(Installation $installation, $output)
    {
        if ($installation->username)
        {
            $this->subModule()->setUsername($installation->username);
        }
        foreach ($this->subModule()->getPlugins($installation) as $record)
        {
            if (!PluginBlockedModel::where("slug", $record['name'])->where('product_id', $this->getHosting()->packageid)->count())
            {
                continue;
            }
            $this->subModule()->getPlugin($installation)->delete($record['name']);
            Helper\infoLog(sprintf("Blocked Plugin '%s' has been automatically deleted, Installation ID #%s, Client ID #%s, Hosting ID #%s\r\n",
                $record['name'], $installation->id, $installation->user_id, $installation->hosting_id));
            $output->writeln(sprintf("Blocked Plugin '%s' has been automatically deleted, Installation ID #%s, Client ID #%s, Hosting ID #%s", $record['name'], $installation->id, $installation->user_id, $installation->hosting_id));
        }

    }

    private function loopHostings($product)
    {
        $synchronizedTasks = 0;
        $query = Hosting::where('packageid', $product->id)
            ->select("id")
            ->where('domainstatus', 'Active');
        foreach ($query->get() as $hosting)
        {
            $this->reset();
            try
            {
                $this->setHostingId($hosting->id);
                //is enabled
                if (!$this->getHosting()->productSettings->isPluginBlockedDelete())
                {
                    continue;
                }
                //last run
                if (!$this->scanInteral())
                {
                    continue;
                }
               $synchronizedTasks += $this->loopInstallations($hosting);
            }
            catch (Exception $ex)
            {
                $this->io->error($ex->getMessage());
            }

            return $synchronizedTasks;
        }
    }

    private function loopInstallations($hosting)
    {
        $synchronizedTasks = 0;
        //get installations for hosting
        foreach (Installation::where("hosting_id", $hosting->id)->get() as $installation)
        {
            $this->output->writeln(sprintf("Synchronize Installation: %s", $installation->id));
            /* @var $installation Installation */
            try
            {
                //delete blocked  plugins
                $this->installationProcess($installation, $this->output);
                $synchronizedTasks++;
                $this->output->writeln(sprintf("Installation: %s has been synchronized", $installation->id));
            }
            catch (Exception $ex)
            {
                Helper\errorLog(sprintf("Delete Blocked Plugin:  '%s', Installation ID #%s, Client ID #%s, Hosting ID #%s\r\n", $ex->getMessage(), $installation->id, $installation->user_id, $installation->hosting_id));
                $this->io->error($ex->getMessage());
            }
        }

        return $synchronizedTasks;
    }

    private function setInterfaces($input, $output, $io)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->io     = $io;
    }
}
