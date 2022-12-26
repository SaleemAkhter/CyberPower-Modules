<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Forms;

use ModulesGarden\Servers\DirectAdminExtended\App\UI\Admin\ProductConfiguration\Providers\ConfigurableOptionManager;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Sections\HalfModalColumn;
use ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;

/**
 * Description of RebuildVM
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 */
class CreateConfigurableAction extends BaseForm implements AdminArea
{
    use Lang;

    protected $id    = 'createConfigurableAction';
    protected $name  = 'createConfigurableAction';
    protected $title = 'createConfigurableAction';

    public function getClass()
    {
        
    }

    public function initContent()
    {
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new ConfigurableOptionManager());
        $this->loadDataToForm();
        $dataProvider = $this->getFormData();

        $this->loadLang();
        $this->lang->addReplacementConstant('configurableOptionsNameUrl', '<a style="    color: #31708f; text-decoration: underline;" href="https://docs.whmcs.com/Addons_and_Configurable_Options" target="blank">here</a>');

        $this->addInternalAlert('configurableOptionsNameInfo', AlertTypesConstants::INFO, AlertTypesConstants::SMALL);
        $this->parseData($dataProvider);

        $this->loadDataToForm();
    }

    public function parseData($data)
    {
        if (is_array($data['fields']))
        {
            $length = floor(count($data['fields']) / 2);

            $rightSide = array_slice($data['fields'], 0, $length);
            $leftSide = array_slice($data['fields'], $length);

            $this->addSection($this->getLeftSide($leftSide));
            $this->addSection($this->getRightSide($rightSide));
        }
    }

    public function getLeftSide($leftSideData)
    {
        $leftSection        = new HalfModalColumn('leftSection');
        $leftSection->setMainContainer($this->mainContainer);

        foreach($leftSideData as $key => $name)
        {
            $leftSection->addField((new \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher($key))->setDefaultValue('on')->setRawTitle($key . '|' . $name));
        }
        return $leftSection;
    }

    public function getRightSide($rightSideData)
    {
        $rightSection        = new HalfModalColumn('rightSection');
        $rightSection->setMainContainer($this->mainContainer);

        foreach($rightSideData as $key => $name)
        {
            $rightSection->addField((new \ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\Switcher($key))->setDefaultValue('on')->setRawTitle($key . '|' . $name));
        }
        return $rightSection;
    }


}
