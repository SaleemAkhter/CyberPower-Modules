<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Http\Admin\Server;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Backups\Pages\BackupsList;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages\AdminPanel;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Home\Pages\ControlPanel;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Product\Pages\ServerInformation;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Network\Pages\NetworkInformation;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Rebuild\Pages\RebuildPage;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Snapshots\Pages\SnapshotList;
use ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Tasks\Pages\TaskList;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Http\AbstractHooksClient;

/**
 * Description of Clientsservices
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class ProductPage extends AbstractHooksClient {

    public function index() {
        return Helper\view()
                        ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\CronInformation::class)
                        ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\Form::class)
                        ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\SMTPForm::class)
                        ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\EmailOptions::class)
                        ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\Features::class)
                       ->addElement(\ModulesGarden\Servers\DigitalOceanDroplets\App\UI\Configuration\Pages\ConfigurableOptions::class);
    }

    public function adminServicesTabFields() {
        try {
            return Helper\view()
                            ->addElement(ControlPanel::class)
                            ->addElement(AdminPanel::class)
                            ->addElement(ServerInformation::class)
                            ->addElement(NetworkInformation::class)
                            ->addElement(SnapshotList::class)
                            ->addElement(BackupsList::class)
                            ->addElement(RebuildPage::class)
                            ->addElement(TaskList::class)
                            ->setWhmcsParams((new \ModulesGarden\Servers\DigitalOceanDroplets\App\Helpers\ServiceManager())->getParams());
        }
        catch (Exception $ex) {
        }
    }

}
