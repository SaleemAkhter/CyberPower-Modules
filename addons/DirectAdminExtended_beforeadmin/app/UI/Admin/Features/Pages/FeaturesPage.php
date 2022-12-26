<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Pages;

use ModulesGarden\DirectAdminExtended\App\Models\FunctionsSettings;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Others\Label;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features\Helpers\FeaturesHelper;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Features;
use WHMCS\Database\Capsule as DB;

class FeaturesPage extends DataTable implements AdminArea
{

    const APP_LIST = "applist";

    protected $id    = 'featuresPage';
    protected $name  = 'featuresPage';
    protected $title = null;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id', 'tblproducts', ['id']))->setSearchable(true, Column::TYPE_INT)->setOrderable('ASC'))
                ->addColumn((new Column('fullname', 'tblproducts', ['name']))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('autoinstaller', 'DirectAdminExtended_FunctionsSettings', ['apps_installer_type']))->setOrderable())
                ->addColumn((new Column('enabled_features')));
    }

    public function replaceFieldId($key, $row)
    {
        return $row->pid;
    }

    public function replaceFieldAutoinstaller($key, $row)
    {

        switch($row->apps_installer_type)
        {
            case '1';
                return 'Softaculous';
            case '2':
                return 'Installatron';
            default:
                return '';
        }
    }

    public function replaceFieldFullname($key, $row)
    {
        return '<a href="configproducts.php?action=edit&id=' . $row->pid . '">' . $row->$key . '</a>';
    }

    public function replaceFieldInstaller_type($key, $row)
    {
        return FeaturesHelper::parseInstallerType($row->$key);
    }

    public function replaceFieldEnabled_features($key, $row)
    {
        $featuresCounter = FeaturesHelper::countEnabledFeatures($row);
        $label           = new Label();
        $label->initIds('label')
                ->setTitle((string)$featuresCounter)
                ->setColor('e5fff4')
                ->setBackgroundColor('00b3ce');
        return $label->getHtml();
    }

    public function initContent()
    {
        /* @description if exist request about download , return appList */
        if($this->getRequestValue('download') === FeaturesPage::APP_LIST)
        {
            $provider = new Features\Providers\FeaturesProvider();
            $provider->read();
        }

        $configurationButton = new Buttons\ButtonRedirect();
        $configurationButton->initIds('moveToConfigurationPage')
                ->setIcon('icon-in-button lu-zmdi lu-zmdi-edit')
                ->setRawUrl('addonmodules.php?module=DirectAdminExtended&mg-page=features&mg-action=configuration')
                ->setRedirectParams(['pid' => ':pid']);

        $downloadAppsButton = new Features\Buttons\DownloadApps('downloadAppsButton');
        $downloadAppsButton->setRawUrl(BuildUrl::getUrl('Features', '') . '&mgFormAction=read&download=applist')
                            ->setRedirectParams(['installer' => ':apps_installer_type','pid' => ':pid']);



        $this->addMassActionButton(new Features\Buttons\MassCopy())
                ->addActionButton($downloadAppsButton)
                ->addActionButton(new Features\Buttons\Copy())
                ->addActionButton($configurationButton);
    }

    protected function loadData()
    {
        $tblproductsModel       = new Whmcs\Product();
        $tblproductsgroupsModel = new Whmcs\ProductGroup();
        $functionsettingsModel  = new FunctionsSettings();

        $query = $tblproductsModel->query()->getQuery();

        $query->select([
                    $functionsettingsModel->getTable() . '.*',
                    'tblproducts.id as pid',
                    DB::raw('CONCAT(`tblproductgroups`.`name`," - ",`tblproducts`.`name`) as fullname'),
                    'tblproducts.name',
                    'tblproductgroups.name AS product_group',
                ])
                ->join('tblproductgroups', 'tblproductgroups.id', '=', 'tblproducts.gid')
                ->leftJoin($functionsettingsModel->getTable(), 'tblproducts.id', '=', $functionsettingsModel->getTable() . '.product_id')
                ->where('tblproducts.servertype', '=', 'directadminExtended');

        $dataProv = new QueryDataProvider();
        $dataProv->setData($query);
        $dataProv->setDefaultSorting('id', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
