<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Subdomains\Pages;


use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\TabsWidget\TabsWidget;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper;

class Logs extends TabsWidget implements ClientArea
{
    use Lang;
    use RequestObjectHandler;

    protected $id    = 'logsTabs';
    protected $name  = 'logsTabs';
    protected $title = 'logsTabs';

    protected $tabs  = [
        ErrorLog::class,
        UsageLog::class,
    ];

    public function initContent()
    {
        $subdomain = json_decode(base64_decode($this->getRequestValue('subdomain')));

        $this->loadLang();
        $this->lang->addReplacementConstant('domain', $subdomain->domain);

        foreach ($this->tabs as $tab)
        {
            $this->addElement(Helper\di($tab));
        }
    }
}