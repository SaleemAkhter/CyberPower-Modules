<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Pages;

use ModulesGarden\DirectAdminExtended\Core\Helper\BuildUrl;
use ModulesGarden\DirectAdminExtended\Core\Lang\Lang;
use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard;

class ServersPage extends DataTable implements AdminArea
{
    protected $id         = 'serversPage';
    protected $name       = 'serversPage';
    protected $title      = 'serversPage';
    protected $searchable = false;

    protected function loadHtml()
    {
        $this->addColumn((new Column('id'))->setOrderable('ASC'))
                ->addColumn((new Column('name'))->setOrderable())
                ->addColumn((new Column('accounts_count'))->setOrderable())
                ->addColumn((new Column('type'))->setOrderable());
    }

    public function replaceFieldIpaddress($colName, $row)
    {
        $protocol = $row['secure'] == 'on' ? 'https' : 'http';

        return $protocol . '://' . $row[$colName] . ':' . 2222 . '/CMD_LOGIN';
    }

    public function replaceFieldPassword($colName, $row)
    {
        return decrypt($row[$colName]);
    }

    public function replaceFieldType($key, $row)
    {
        return ServiceLocator::call('lang')->absoluteTranslate($row[$key]);
    }

    public function initContent()
    {
        $loginToWhm = new Buttons\ButtonRedirect();
        $loginToWhm
                ->initIds('loginToDirectAdmin')
                ->setName('loginToDirectAdmin')
                ->setIcon('icon-in-button lu-zmdi lu-zmdi-square-right')
                ->setRawUrl(BuildUrl::getUrl('Dashboard', 'loginToDirectAdmin', [], false))
                ->setRedirectParams([
                    'id' => ':id'
                ])
                ->addHtmlAttribute('data-toggle', 'lu-tooltip');
                //->addHtmlAttribute('title', Lang::getInstance()->T('addonAA','dashboard','serverpage','button','loginWhm'));
               // ->addHtmlAttribute('title', 'Log in to DirectAdmin');

        $editServer = new Buttons\ButtonRedirect();
        $editServer
                ->initIds('editServer')
                ->setIcon('icon-in-button lu-zmdi lu-zmdi-edit')
                ->setRawUrl('configservers.php?action=manage')
                ->setRedirectParams(['id' => ':id']);

        $this->addActionButton($loginToWhm)
            ->addActionButton($editServer);
    }

    protected function loadData()
    {
        $query    = 'SELECT tblservers.id, tblservers.name, tblservers.secure, tblservers.username, tblservers.ipaddress, tblservers.password, tblservers.type, CONCAT(COUNT(hosting.id),"/", tblservers.maxaccounts)  AS accounts_count FROM tblservers
                    LEFT JOIN (
                        SELECT tblhosting.id AS id, tblhosting.domainstatus, tblhosting.server FROM tblhosting 
                        INNER JOIN tblservers ON tblhosting.server = tblservers.id 
                        WHERE tblservers.type IN ("directadmin", "directadminExtended") AND tblhosting.domainstatus = "Active" 
                    ) hosting ON hosting.server = tblservers.id
                    WHERE tblservers.type IN ("directadmin", "directadminExtended") 
                         AND (hosting.domainstatus = "Active" OR hosting.domainstatus IS NULL)
                    GROUP BY tblservers.id';
        $dataProv = new QueryDataProvider();
        $dataProv->setData($query);
        $dataProv->setDefaultSorting('id', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
