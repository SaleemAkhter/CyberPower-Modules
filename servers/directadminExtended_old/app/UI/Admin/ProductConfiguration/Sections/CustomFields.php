<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 02.04.2019
 * Time: 08:21
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Buttons\CreateCustomFieldsBaseModalButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers\CustomFieldsProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;

class CustomFields extends BoxSection implements AdminArea
{
    protected $id = 'customFields';
    protected $name = 'customFields';
    protected $title = 'customFields';

    protected $columns = 3;

    public function initContent()
    {
        $this->addButton((new CreateCustomFieldsBaseModalButton()));
    }

    public function getOptions()
    {
        $fields = (new CustomFieldsProvider())->getCustomFields();
        $fields['emptyFields'] = "";
       return $fields;
    }
}