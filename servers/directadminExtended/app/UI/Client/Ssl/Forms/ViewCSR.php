<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Forms;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssl\Providers;

class ViewCSR extends BaseForm implements ClientArea
{
    protected $id    = 'viewCsrForm';
    protected $name  = 'viewCsrForm';
    protected $title = 'viewCsrForm';

    public function initContent()
    {
        $this->addDefaultActions(FormConstants::READ)
                ->setProvider(new Providers\Csr());

        $csr    = (new Fields\Textarea('csr'))
            ->addHtmlAttribute('readonly', 'readonly')
            ->setRows(12);


        $this
            ->addInternalAlert('CSRInfo', AlertTypesConstants::INFO, AlertTypesConstants::DEFAULT_SIZE)
            ->addField($csr)
            ->loadDataToForm();
    }

}
