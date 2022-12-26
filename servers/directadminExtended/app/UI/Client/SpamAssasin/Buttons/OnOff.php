<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Buttons;

use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonSwitchAjax;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class OnOff extends ButtonSwitchAjax implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id    = 'onOff';
    protected $name  = 'onOff';
    protected $title = 'onOff';

    protected $value = 'off';

    protected $switchOnValue    = true;


    public function initContent()
    {
        $this->setTitle('onOff');
        $this->htmlAttributes['onclick'] = 'spamassassinForm(event)';
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {

    }

    public function getValue()
    {
        return $this->value;
    }

    public function setSwitcherDisable($value){
        if($value === false)
        {
            $this->value = "on";
        }
        else {
            $this->value = "off";
        }
    }


    public function returnAjaxData()
    {
        $this->loadUserApi();
        $switcherValue = $this->getRequestValue('value');
        try
        {
            $data = [
                'domain' => $this->getWhmcsParamByKey('domain')
            ];

            if ($switcherValue === 'on')
            {

            }
            else
            {
                $this->userApi->spamassassin->disable(new Models\Command\Spamassassin($data));
            }
        }
        catch (\Exception $ex)
        {
            return (new ResponseTemplates\DataJsonResponse())
                ->setStatusError()
                ->setMessage($ex->getMessage());

        }

        $message = 'spamassassinDisabled';
        if ($switcherValue === 'on')
        {
            $message = 'spamassassinEnabled';
        }

        return (new ResponseTemplates\DataJsonResponse())
            ->setMessageAndTranslate($message)
            ->setData(['value' => $switcherValue, 'elementID' => $this->id])
            ->setCallBackFunction('switchForm');
    }

}
