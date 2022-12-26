<?php

namespace ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Buttons;


use ModulesGarden\OvhVpsAndDedicated\Core\Traits\Lang;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Interfaces\AdminArea;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\Widget\Buttons\ButtonSwitchAjax;
use ModulesGarden\OvhVpsAndDedicated\Core\UI\ResponseTemplates;
use ModulesGarden\OvhVpsAndDedicated\App\UI\Admin\Servers\Providers\Vps as VpsProvider;

/**
 * Class VpsReuse
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class ReuseButton extends ButtonSwitchAjax implements AdminArea
{
    use Lang;
    protected $actionIdColumn = 'name';
    protected $switchColumn   = 'reuse';
    protected $switchOnValue  = 'on';
    protected $switchOffValue = 'off';


    public function __construct($baseId = null)
    {
        parent::__construct($baseId);
        $this->loadLang();
        $this->setTitle($this->lang->absoluteTranslate('machine', 'button', 'reuse', 'info'));
    }

    public function returnAjaxData()
    {
        try
        {
            $provider = new VpsProvider();
            $provider->setReuse();

            $this->loadLang();
            return (new ResponseTemplates\DataJsonResponse())->setMessage($this->lang->translate('machine', 'reuse', 'status', 'success'));
        }
        catch (\Exception $exc)
        {
            return (new ResponseTemplates\DataJsonResponse())->setStatusError()->setMessage($exc->getMessage());
        }
    }
}
