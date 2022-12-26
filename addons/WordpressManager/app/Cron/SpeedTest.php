<?php

namespace ModulesGarden\WordpressManager\App\Cron;

use Exception;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use ModulesGarden\WordpressManager\App\Models\WebsiteDetails;
use ModulesGarden\WordpressManager\App\Services\GooglePreviewAPI;
use ModulesGarden\WordpressManager\Core\CommandLine\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Illuminate\Support\Carbon;
use ModulesGarden\WordpressManager\Core\Helper;

class SpeedTest extends Command
{
    protected const HOURS = 24;
    protected $name = 'speedtest';
    protected $description = '';
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
        $io->title('Website Speed Test: Running');


        try {
            $this->doSpeedTest($this->getSites());
        } catch (Exception $ex) {
            $io->error($ex->getMessage());
            Helper\errorLog(sprintf(
                $ex->getMessage()));
        }

        if (!$ex) {
            $io->success([
                sprintf("Speed Test has been Finished")
            ]);
        }
    }

    private function doSpeedTest(array $sites)
    {
        $token = $this->checkToken();

        $api = new GooglePreviewAPI($token);

        foreach ($sites as $site) {
            if ($this->isTimeUp($site['id'])) {
                $this->output->writeln(sprintf("Trying to run the page speed test for %s ", $site['url']));

                try {
                    $speedtest = $api->getGooglePageSpeedData($site['url']);
                    $this->io->success([
                        sprintf("Speed test for %s has been completed ", $site['url'])
                    ]);
                    $this->save($site['id'], $speedtest);
                } catch (Exception $ex) {
                    $this->io->error($ex->getMessage());
                }
            }
        }
    }

    private function isTimeUp(int $id)
    {
        $hours         = ModuleSettings::where('setting', 'cron')->value('value') ?? SpeedTest::HOURS;
        $lastSpeedTest = $this->getLastSpeedTest($id);

        if ($lastSpeedTest) {
            $lastSpeedTest = $lastSpeedTest->addHours($hours);

            if (Carbon::now() >= $lastSpeedTest) {
                return true;
            }
            return false;
        }

        return true;
    }

    private function getLastSpeedTest(int $id)
    {
        $lastSpeedTest = WebsiteDetails::find($id) ? (WebsiteDetails::find($id))->created_at : false;
        return $lastSpeedTest;
    }

    private function save($id, array $speedTest)
    {
        $websiteDetails = WebsiteDetails::find($id);

        if ($websiteDetails == null) {
            (new WebsiteDetails([
                'wpid'       => $id,
                'desktop'    => $speedTest['desktop'],
                'mobile'     => $speedTest['mobile'],
                'screenshot' => $speedTest['screenshot'],
            ]))->save();
            return;
        }

        $websiteDetails->desktop    = $speedTest['desktop'];
        $websiteDetails->mobile     = $speedTest['mobile'];
        $websiteDetails->screenshot = $speedTest['screenshot'];
        $websiteDetails->save();
    }

    private function getSites()
    {
        $instalations = Installation::all();

        $sites = [];
        foreach ($instalations as $in) {
            $sites[] = [
                'id'  => $in->id,
                'url' => $in->url,
            ];
        }

        return $sites;
    }

    private function setInterfaces($input, $output, $io)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->io     = $io;
    }

    private function checkToken()
    {
        $token = (new ModuleSettings())->getSettings()['googleApiToken'];

        if (!$token) {
            throw new Exception('API Token does not exist', 404);            
        }

        return $token;
    }
}
