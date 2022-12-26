<?php

namespace ModulesGarden\WordpressManager\App\UI\LoggerManager\Providers;

use ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

class OtherSettingsProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct('ModulesGarden\WordpressManager\App\Models\ModuleSettings');
    }

    public function read()
    {
        $moduleSettings = new ModuleSettings();
        $this->availableValues['protocols'] = ['1' => 'http://', '2' => 'http://www', '3' => ' https://', '4' => ' https://www'];
        $this->data['protocols'] = $moduleSettings->getProtocols();

        $settings = $moduleSettings->getSettings();
        $this->data['googleApiToken'] = $settings['googleApiToken'];
        $this->data['cron']           = $settings['cron'];
        $this->data['extendedView']   = $settings['extendedView'];

        $this->availableValues['cron']  = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            6 => 6,
            8 => 8,
            10 => 10,
            12 => 12,
            14 => 14,
            16 => 16,
            18 => 18,
            20 => 20,
            22 => 22,
            24 => 24,
            36 => 36,
            48 => 48
        ];
    }

    public function update()
    {
        $moduleSettings = new ModuleSettings();
        $moduleSettings->saveProtocols((array) $this->formData['protocols']);

        $setting['googleApiToken'] = $this->formData['googleApiToken'];
        $setting['cron']           = $this->formData['cron'];
        $setting['extendedView']   = $this->formData['extendedView'];

        $moduleSettings->setSettings($setting);

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('your changes has been saved successfully');
    }
}
