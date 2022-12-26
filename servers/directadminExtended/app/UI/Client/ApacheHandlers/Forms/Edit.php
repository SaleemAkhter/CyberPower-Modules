<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\ApacheHandlers\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

class Edit extends BaseForm implements ClientArea
{
    protected $id    = 'editForm';
    protected $name  = 'editForm';
    protected $title = 'editForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::UPDATE)
                ->setProvider(new Providers\ApacheHandlersEdit());

        $this->loadFields()
            ->loadDataToForm();
    }

    protected function loadFields()
    {
        $domain    = (new Fields\Text('domain'))->disableField();
        $handler    = (new Fields\Text('handler'))->disableField();
        $extensions = new Fields\Text('extensions');
        $extensions->setDescription('description');
        $extHidden  = new Fields\Hidden('extHidden');
        $hanHidden  = new Fields\Hidden('hanHidden');
        $domainHidden  = new Fields\Hidden('domainHidden');

        $this->addField($domain)
            ->addField($handler)
            ->addField($extensions)
            ->addField($extHidden)
            ->addField($hanHidden)
            ->addField($domainHidden);

        return $this;
    }
}
