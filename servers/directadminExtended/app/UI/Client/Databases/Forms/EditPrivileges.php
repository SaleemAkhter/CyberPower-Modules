<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Forms;

use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\HalfPageSection;

class EditPrivileges extends BaseForm implements ClientArea
{
    protected $id    = 'editFormPrivileges';
    protected $name  = 'editFormPrivileges';
    protected $title = 'editFormPrivileges';

    /**
     *
     */
    public function initContent()
    {
        $provider   = new \ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Databases\Providers\UserPrivileges();

        $this->setFormType(FormConstants::UPDATE)
                ->setProvider($provider);


        $column1 = new HalfPageSection('column1');
        $column2 = new HalfPageSection('column2');


        $counter = 0;
        foreach ($provider->getPrivileges() as $option)
        {

            if ($counter >= (count($provider->getPrivileges()) / 2))
            {
                $column2->addField(new Switcher($option));
                continue;
            }
            $column1->addField(new Switcher($option));
            $counter++;
        }


        $column1->addField(new Hidden('user'));
        $column1->addField(new Hidden('database'));

        $this->addSection($column1)
            ->addSection($column2)
            ->loadDataToForm();
    }
}


