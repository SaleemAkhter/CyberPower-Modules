<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Pages;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanClone;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanEdit;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\Installers\Installers\Interfaces\CanPushStaging;
use ModulesGarden\Servers\DirectAdminExtended\App\Traits\DirectAdminAPIComponent;
use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonWordpressRedirect;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\RawDataTableApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Interfaces\ClientArea;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Buttons;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ApplicationInstaller;
use Symfony\Component\DependencyInjection\Tests\Compiler\C;

class ApplicationsTable extends RawDataTableApi implements ClientArea
{
    use DirectAdminAPIComponent;

    protected $id = 'ApplicationsTable';
    protected $name = 'ApplicationsTable';
    protected $title = 'ApplicationsTab';
    protected $installer;
    protected $installerName;

    protected function loadHtml()
    {
        $this->addColumn((new Column('name', null, ['name']))->setSearchable(true)->setOrderable('ASC'))
            ->addColumn(new Column('domain'))
            ->addColumn(new Column('time'))
            ->addColumn(new Column('version'));
    }

    public function initContent()
    {
        $applicationHelper = new ApplicationInstaller($this->loadRequiredParams());
        $this->installer = $applicationHelper->getInstaller();
        $this->installerName = $applicationHelper->getInstallerName();

        if (strtolower($applicationHelper->getInstallerName()) !== 'default') {

            $this->addActionButton(new Buttons\LoginToWordpress());

            if ($this->installer instanceof CanPushStaging) {
                $this->addActionButton(new Buttons\StagingApp())
                    ->addActionButton(new Buttons\PushToLive());
            }

            if ($this->installer instanceof CanClone) {
                $this->addActionButtonToDropdown(new Buttons\CloneApp());
            }

            $this->addActionButtonToDropdown(new Buttons\CreateBackup());

            if($this->installer instanceof CanEdit) {
                $this->addActionButtonToDropdown(new Buttons\EditAppButton());
            }

            $this->addActionButtonToDropdown(new Buttons\Delete());
        }
    }

    public function replaceFieldName($key, $row)
    {
        return '<img  width="30px" height="30px" src="' . $row['image'] . '"/> ' . $row['name'] . ' ' . $row['version'];
    }

    public function replaceFieldTime($key, $row)
    {
        return $row['time'] ? date('F d Y h:i a', $row['time']) : '-';
    }

    protected function loadData()
    {
        $result = $this->installer->getInstallations();
        $result = $this->convertToArray($result);
        $result = $this->addHideWordpressField($result);

        $provider = new ArrayDataProvider();

        $provider->setData($result);
        $provider->setDefaultSorting('name', 'ASC');

        $this->setDataProvider($provider);
    }

    private function convertToArray($data)
    {
        if (is_array($data) || is_object($data)) {

            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->convertToArray($value);
            }
            return $result;
        }

        return $data;
    }

    private function addHideWordpressField($data)
    {
        foreach ($data as &$array) {
            $array['hideWordpress'] = (strpos($array['id'], '26_') !== false) ? '0' : '1';
        }

        return $data;
    }
}
