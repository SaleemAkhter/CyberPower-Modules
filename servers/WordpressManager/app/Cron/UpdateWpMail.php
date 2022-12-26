<?php

namespace ModulesGarden\WordpressManager\App\Cron;

use DateTime;
use Exception;
use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\UpdateMails;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Services\CronService;
use ModulesGarden\WordpressManager\App\Services\EmailService;
use ModulesGarden\WordpressManager\Core\CommandLine\Command;
use ModulesGarden\WordpressManager\Core\Helper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateWpMail extends Command
{

    use main\App\Http\Admin\BaseAdminController;

    /**
     * Command name
     * @var string
     */
    protected $name = 'updateWpMail';

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
        $this->setInterfaces($input, $output, $io);
        $this->io->title('Sending Update WordPress Email Notifications: Running');

        $synchronizeTasks = $this->synchronizeTasks();

        $this->output->writeln("");
        $this->io->success([
            sprintf("Sent Update WordPress Email Notifications: %s Messages.", $synchronizeTasks),
            "Sending Update Wordpress Email Notifications: Done"
        ]);
        if (is_null($this->hosting))
        {
            return;
        }
        $settings                                       = $this->hosting->productSettings->getSettings();
        $settings['UpdateWpVersionNotificationLastRun'] = date("Y-m-d H:i:s");
        $this->hosting->productSettings->setSettings($settings)->save();
    }

    private function synchronizeTasks()
    {
        $synchronizeTasks = 0;
        foreach (CronService::getProductsEnabled() as $product)
        {
            $synchronizeTasks += $this->loopHostings($product);
        }

        return $synchronizeTasks;
    }

    private function loopHostings($product)
    {
        $synchronizeTasks = 0;
        //Get hosting
        $query = Hosting::where('packageid', $product->id)
            ->select("id")
            ->where('domainstatus', 'Active');

        foreach ($query->get() as $hosting)
        {
            try
            {
                $this->reset();
                $this->setHostingId($hosting->id);
                $this->getHosting();
                $synchronizeTasks += $this->processHostingNotifications($hosting, $synchronizeTasks);
            }
            catch (Exception $ex)
            {
                $this->io->error($ex->getMessage());
            }
        }

        return $synchronizeTasks;
    }

    private function processHostingNotifications($hosting, $synchronizeTasks)
    {
        if (!$this->sendNotificationMailsInterval() || !$this->hosting->productSettings->isWpUpdateNotificationMailEnabled())
        {
            return;
        }

       return $this->loopInstallations($hosting, $synchronizeTasks);
    }

    private function sendNotificationMailsInterval()
    {
        if (!$this->hosting->productSettings->getUpdateWpVersionNotificationInterval())
        {
            return true;
        }

        if (!$this->hosting->productSettings->getUpdateWpVersionNotificationLastRun())
        {
            return true;
        }

        $lastRun = new DateTime($this->hosting->productSettings->getUpdateWpVersionNotificationLastRun());
        $dDiff   = $lastRun->diff(new DateTime());
        return $dDiff->h >= $this->hosting->productSettings->getUpdateWpVersionNotificationInterval();
    }

    private function loopInstallations($hosting, int $synchronizeTasks)
    {
        $synchronizeTasks = 0;
        foreach (Installation::where("hosting_id", $hosting->id)->get() as $installation)
        {
            $this->processInstallionEmail($installation, $hosting->id) ?: $synchronizeTasks++;
        }

        return $synchronizeTasks;
    }

    private function processInstallionEmail($installation, $hostingId)
    {
        if (!(new CheckWpVersion)->checkIfNewer($installation->version))
        {
            return false;
        }

        $wasEmailSent = (new UpdateMails())->where('user_id', $installation->user_id)->where('current_version', $installation->version)->pluck('id')->toArray();
        if($wasEmailSent)
        {
            return false;
        }

        $emailService = new EmailService();
        $emailService->template($this->hosting->productSettings->getUpdateWpVersionNotificationTemplate());
        $emailService->vars([
            'id'    => $hostingId
        ])->send();

        $updateMails                  = new UpdateMails();
        $updateMails->user_id         = $installation->user_id;
        $updateMails->current_version = $installation->version;
        $updateMails->save();

        Helper\infoLog(sprintf(
            "An email reminding about the available WordPress update has been sent, Client ID #%s, Current version: %s\r\n",
            $installation->user_id,
            $installation->version
        ));

        return true;
    }

    private function setInterfaces($input, $output, $io)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->io     = $io;
    }
}
