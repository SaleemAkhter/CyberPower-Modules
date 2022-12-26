<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\WhmcsParams;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;


/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Reboot extends BaseDataProvider implements ClientArea, AdminArea
{

    use WhmcsParamsApp;
    /**
     * @var Server
     */
    private $server = null;

    public function read()
    {

//        $ids = $this->getAvailableBootIds();
//        $this->availableValues['type'] = $this->getTypesToForm($ids);
    }

    public function create()
    {

    }

    public function delete()
    {

    }

    public function reload()
    {

    }


    public function update()
    {
        try
        {
            $this->getServer()->makeBoot('harddisk');
            $this->getServer()->reboot();
            return (new HtmlDataJsonResponse())->setStatusSuccess()
                ->setMessageAndTranslate('rebootStarted');
        }
        catch (Exception $ex)
        {
            return (new HtmlDataJsonResponse())->setStatusError()->setMessage($ex->getMessage());
        }
    }

    private function getServer()
    {

        if ($this->server)
        {
            return $this->server;
        }

        $repo         = new Dedicated\Repository($this->getAppParams(WhmcsParams::getEssentialsKeys()));
        $this->server = $repo->get();

        return $this->server;
    }
}
