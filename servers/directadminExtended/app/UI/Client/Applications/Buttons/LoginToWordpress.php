<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;


use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;

class LoginToWordpress extends ButtonRedirect implements ClientArea
{
    protected $id    = 'redirectToWordpress';
    protected $name  = 'redirectToWordpress';
    protected $title = 'redirectToWordpress';
    protected $icon  = 'lu-zmdi lu-zmdi-account';

    public function __construct($baseId = null)
    {
        parent::__construct($baseId);

        $this->setRawUrl(BuildUrl::getUrl('Applications', 'wpLogin'))
            ->setRedirectParams(['appId' => ':id'])
            ->setHideByColumnValue('hideWordpress', '1');
    }

    public function initContent()
    {
        $this->htmlAttributes['@click'] = 'redirect($event, ' . $this->parseCustomParams() . ', true)';
    }

}