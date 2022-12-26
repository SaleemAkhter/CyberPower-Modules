<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;
use function \ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;

class ViewKey extends BaseForm implements ClientArea
{
    protected $id    = 'viewForm';
    protected $name  = 'viewForm';
    protected $title = 'viewForm';

    public function initContent()
    {
        $this->setFormType(FormConstants::DELETE)
                ->setProvider(new Providers\SslKeyView());


        $key = (new Fields\Textarea('key'))
            ->addHtmlAttribute('readonly', 'readonly')
            ->setRows(12);

        $this->addField($key)
            ->loadDataToForm();
    }
}
