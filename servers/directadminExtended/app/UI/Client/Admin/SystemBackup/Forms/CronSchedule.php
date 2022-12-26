<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Filters\Text;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\SystemBackup\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\UnlimitedSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Radio;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\LabelField;

class CronSchedule extends BaseForm implements ClientArea
{
    protected $id = 'cronScheduleForm';
    protected $name = 'cronScheduleForm';
    protected $title = 'cronScheduleForm';
    protected $bandwidthSwitcher;
    protected $usageSwitcher;

    public function initContent()
    {
        $this->loadLang();

        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new Providers\CronSchedule());
        $this->loadDataToForm();
        $data=$this->dataProvider->getData();

        $this->addFields();
        $this->loadDataToForm();
    }


    private function addFields()
    {
        $this->addField((new Fields\Checkbox('CRON'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Text('MINUTE'))->setDescription('description'))
        ->addField((new Fields\Text('HOUR'))->setDescription('description'))
        ->addField((new Fields\Text('DAY'))->setDescription('description'))
        ->addField((new Fields\Text('MONTH'))->setDescription('description'))
        ->addField((new Fields\Text('DAYOFWEEK'))->setDescription('description'));

    }



    protected function reloadFormStructure()
    {
        $this->dataProvider->reload();


        $this->loadDataToForm();
    }



    function addFieldAfter($fieldId, $newField)
    {
        $array = $this->getFields();

        $index = array_search($fieldId, array_keys($array)) + 1;

        $size = count($array);
        if (!is_int($index) || $index < 0 || $index > $size) {
            return -1;
        } else {
            $temp = array_slice($array, 0, $index);
            $temp[$newField->getId() . $index] = $newField;
            $this->fields = array_merge($temp, array_slice($array, $index, $size));
        }
    }
}

