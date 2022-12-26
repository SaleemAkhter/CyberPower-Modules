<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Forms;

use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Filters\Text;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator\Numeric;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\ActionSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\Core\Models\ProductSettings\Repository;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseStandaloneForm;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\FormConstants;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\BackupTransfer\Providers;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Sections\InputGroup;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\Fields\InputGroupElements;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\UnlimitedSwitcher;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\Validator;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Forms\BaseForm;

use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\sl;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\Radio;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Customs\Fields\LabelField;

class BackupSetting extends BaseForm implements ClientArea
{
    protected $id = 'editForm';
    protected $name = 'editForm';
    protected $title = 'editForm';
    protected $bandwidthSwitcher;
    protected $usageSwitcher;

    public function initContent()
    {
        $this->loadLang();

        $this->setFormType(FormConstants::UPDATE);
        $this->setProvider(new Providers\BackupSetting());
        // $this->replaceClasses(['shadow1','p-20','col-lg-8']);
        $this->loadDataToForm();
        $data=$this->dataProvider->getData();
        // debug($data);die();
        // $this->addSwitcherSection();
        $this->addFields();
        // $this->addUsage();
        // $this->addBandwidth();
        // $this->addUnlimitedTypeFields();

        $this->loadDataToForm();
    }

//{"message":"yes","confirm_with_domainowners":"yes","json":"yes","action":"setting","backup_ftp_pre_test":"0","backup_ftp_md5":"0","allow_backup_encryption":"0","restore_database_as_admin":"1","tally_after_restore":"1","backup_hard_link_check":"1","webmail_backup_is_email_data":"1"}:
//{"message":"yes","local_ns":"yes","confirm_with_domainowners":"yes","json":"yes","action":"setting","backup_ftp_pre_test":"1","backup_ftp_md5":"1","allow_backup_encryption":"1","restore_database_as_admin":"1","tally_after_restore":"1","backup_hard_link_check":"1","webmail_backup_is_email_data":"1"}:
//{"confirm_with_domainowners":"yes","json":"yes","action":"setting","backup_ftp_pre_test":"1","backup_ftp_md5":"1","allow_backup_encryption":"1","restore_database_as_admin":"1","tally_after_restore":"1","backup_hard_link_check":"1","webmail_backup_is_email_data":"1"}:


    private function addFields()
    {
        $this->addField((new Fields\Checkbox('message'))->setMobileWidth(2)->setLabelWidth(11)->setLabelMobileWidth(2)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Radio('local_ns'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right')->setAvailableValues(['yes'=>'yes','no'=>'no']));
        $this->addField((new Radio('restore_spf'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right')->setAvailableValues(['yes'=>'yes','no'=>'no']));
        $this->addField((new Fields\Checkbox('confirm_with_domainowners'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new LabelField('directadmin.conf')));
        $this->addField((new Fields\Checkbox('backup_ftp_pre_test'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('backup_ftp_md5'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('allow_backup_encryption'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('restore_database_as_admin'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('tally_after_restore'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('backup_hard_link_check'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
        $this->addField((new Fields\Checkbox('webmail_backup_is_email_data'))->setLabelWidth(11)->setWidth(1)->setLabelPosition('right'));
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

