<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\HotlinkProtection\Providers\HotlinkProtectionProvider;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections;

class AddForm extends BaseForm implements ClientArea
{
    protected $id    = 'addForm';
    protected $name  = 'addForm';
    protected $title = 'addForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE)
            ->addDefaultActions(FormConstants::READ)
            ->setProvider(new HotlinkProtectionProvider());

        $url = (new Sections\InputGroup('url'))
            ->addTextField('url');
        $url->getField('url')->setPlaceholder('e.g. https://*.yourdomain.com');

        $domain = (new Hidden('domain'));
        $fileTypes = (new Hidden('fileTypes'));
        $blankReferer = (new Hidden('allowBlankReferer'));
        $redirect = (new Hidden('redirect'));
        $redirectUrl = (new Hidden('redirectUrl'));

        $this->addSection($url)
            ->addField($blankReferer)
            ->addField($domain)
            ->addField($fileTypes)
            ->addField($redirect)
            ->addField($redirectUrl)
            ->loadDataToForm();
    }
}
