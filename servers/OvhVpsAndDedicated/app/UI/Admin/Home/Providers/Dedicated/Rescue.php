<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\UI\Admin\Home\Providers\Dedicated;

use Exception;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Traits\WhmcsParamsApp;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated\Server;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Repository\Dedicated;

/**
 * Description of Rebuild
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Rescue extends BaseDataProvider implements ClientArea, AdminArea
{
    use WhmcsParamsApp;
    use Lang;


    /**
     * @var Server
     */
    private $server = null;

    public function __construct()
    {
        parent::__construct();
        $this->loadLang();
    }

    public function read()
    {

    }

    public function create()
    {
        
    }

    public function delete()
    {
        
    }

    public function update()
    {
        try
        {
            $bootObject = $this->getServer()->boot();
//            $boots = $bootObject->allToModel($this->formData['type']); //zmiana przed release
            $boots = $bootObject->allToModel('rescue');
            $chosen = $boots[0];

            $params = [
                'bootId' => $chosen->getBootId(),
                //                'monitoring' => $this->formData['monitoring'] == 'on' ? true : false,
            ];

            $this->getServer()->update($params);
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

        $repo         = new Dedicated\Repository($this->getWhmcsEssentialParams());
        $this->server = $repo->get();

        return $this->server;
    }

}
