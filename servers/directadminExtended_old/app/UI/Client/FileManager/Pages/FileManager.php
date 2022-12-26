<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Pages;

use ModulesGarden\Servers\DirectAdminExtended\Core\Models\Whmcs\Configuration;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates\RawDataJsonResponse;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\Core\Http\Request;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\DataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\FileManager\Buttons;

class FileManager extends DataTableApi implements ClientArea
{

    protected $id           = 'fileManagerPage';
    protected $name         = 'fileManagerPage';
    protected $title        = 'fileManagerPage';

    protected $defaultVueComponentName = 'mg-file-manager';
    protected $additionalData = [];
    protected $callBackFunction = 'hideFileManagerOptions';

    protected function loadHtml()
    {
        $this->setTableLength(999999);
        $this->addColumn((new Column('name'))->setSearchable(true))
            ->addColumn((new Column('showsize', null))->setSearchable(true))
            ->addColumn((new Column('date', null))->setSearchable(true))
            ->addColumn((new Column('permission', null))->setSearchable(true))
            ->addColumn((new Column('uid', null))->setSearchable(true))
            ->addColumn((new Column('gid', null))->setSearchable(true));
    }

    public function initContent()
    {
        $this->addButton(new Buttons\CreateDirectory())
            ->addButtonToDropdown(new Buttons\Upload());

        $this->addActionButtonToDropdown((new Buttons\Rename()))
            ->addActionButtonToDropdown(new Buttons\Permissions())
            ->addActionButtonToDropdown(new Buttons\Copy())
            ->addActionButtonToDropdown((new Buttons\Protect()))
            ->addActionButtonToDropdown(new Buttons\Delete());

        $this->addMassActionButton(new Buttons\MassAction\MassPermissions())
            ->addMassActionButton(new Buttons\MassAction\MassDelete());

        $this->disabledViewFooter();
    }

    public function customColumnHtmlName()
    {
        $button = new Buttons\ActiveFile();


        return $button;
    }

    public function replaceFieldFileSize($key, $row)
    {
        return $row->getHumanSize();
    }

    public function replaceFieldDate($key, $row)
    {
        $dateFormat = Configuration::where('setting', 'DateFormat')->first();

        $date = date('d-m-Y', $row[$key]);

        if (function_exists('fromMySQLDate') && !is_null($dateFormat)) {
            return \fromMySQLDate($date, false, $dateFormat->value);
        }

        return $date;
    }

    public function replaceFieldModification($key, $row)
    {
        return date('d-m-Y g:i a', $row->getModificationTime());
    }

    public function replaceFieldFilePermissions($key, $row)
    {
        return $row->getPermissions();
    }

    protected function loadData()
    {
        $oldPath = Request::build()->getSession('fileManagerPath');
        $delimiter = stripos($oldPath, '\\') !== false ? '\\' : '/';

        $additionalData = explode($delimiter, $oldPath);
        $this->customTplVars['additionalData']['currentDirPath'] = $additionalData;
        $this->setAdditionalData(['currentDirPath' => $additionalData]);

        $this->loadUserApi();

        $data = [
            'path' => '/' . $oldPath
        ];
        $result     = $this->userApi->fileManager->lists(new Models\Command\FileManager($data))->toArray();

        foreach ($result as $key => $element) {
            $result[$key]['id'] = base64_encode(json_encode($element));
        }
        $provider   = new ArrayDataProvider();
        $provider->setData($result);



        $this->setDataProvider($provider);
    }

    public function getTableLength()
    {
        return $this->tablelength;
    }

    /**
     * @return array
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param $additionalData
     * @return $this
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;

        return $this;
    }

    //TODO - change it in the future


    public function returnAjaxData()
    {
        $this->loadHtml();
        $this->loadData($this->columns);

        $this->parseDataRecords();

        $returnTemplate = self::getVueComponents();

        return (new RawDataJsonResponse([
            'recordsSet' => $this->recordsSet, 'template' => $returnTemplate,
            'registrations' => self::getVueComponentsRegistrations(), 'additionalData' => $this->getAdditionalData()
        ]))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
    }
}
