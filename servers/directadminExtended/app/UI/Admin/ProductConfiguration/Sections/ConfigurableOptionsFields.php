<?php
/**
 * Created by PhpStorm.
 * User: Mateusz Pawłowski
 * Date: 02.04.2019
 * Time: 08:21
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Sections;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Buttons\CreateConfigurableOptionsBaseModalButton;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Helper\ConfigurableOptionsBuilder;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\SpamAssasin\Forms\Sections\BoxSection;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;

class ConfigurableOptionsFields extends BoxSection implements AdminArea
{
    protected $id = 'configurableOptionsFields';
    protected $name = 'configurableOptionsFields';
    protected $title = 'configurableOptionsFields';

    public function initContent()
    {
        $this->addButton((new CreateConfigurableOptionsBaseModalButton()));
    }

    public function getOptions()
    {
        $configurableOptions = new \ModulesGarden\Servers\DirectAdminExtended\App\Services\ConfigurableOptions\ConfigurableOptions($this->getRequestValue('id'));

        ConfigurableOptionsBuilder::buildAll($configurableOptions);

        $fields = $configurableOptions->show();

        $fields['emptyFields'] = "";

        return $fields;
    }
}