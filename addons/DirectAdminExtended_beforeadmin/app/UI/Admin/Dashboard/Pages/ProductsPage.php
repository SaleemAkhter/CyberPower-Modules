<?php

namespace ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Pages;

use ModulesGarden\DirectAdminExtended\App\UI\Admin\Dashboard\Buttons\Upgrade;
use ModulesGarden\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs;
use ModulesGarden\DirectAdminExtended\Core\ServiceLocator;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\Column;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataProviders\Providers\QueryDataProvider;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\DataTable\DataTable;
use ModulesGarden\DirectAdminExtended\Core\UI\Interfaces\AdminArea;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Hosting;
use ModulesGarden\DirectAdminExtended\Core\UI\Widget\Others\Label as Lab;

class ProductsPage extends DataTable implements AdminArea
{
    protected $id    = 'productsPage';
    protected $name  = 'productsPage';
    protected $title = 'productsPage';

    protected $types = [
        'directadmin',
        'directadminExtended'
    ];
    
    protected $stats = [
        '1dc12d' => 'Active',
        '0d60ad' => 'Suspended',
        'c10505' => 'Terminated'
    ];

    protected function loadHtml()
    {
        $this->addColumn((new Column('id', 'tblproducts'))->setSearchable(true, Column::TYPE_INT)->setOrderable('ASC'))
            ->addColumn((new Column('groupName', 'tblproductgroups', ['name']))->setSearchable(true)->setOrderable())
            ->addColumn((new Column('productName', 'tblproducts', ['name']))->setSearchable(true)->setOrderable())
            ->addColumn((new Column('serverType', 'tblproducts'))->setSearchable(true)->setOrderable())
            ->addColumn(new Column('stats'));
    }

    public function initContent()
    {
        $editProduct = new ButtonRedirect();
        $editProduct
            ->initIds('editProduct')
            ->setIcon('icon-in-button lu-zmdi lu-zmdi-edit')
            ->setRawUrl('configproducts.php?action=edit')
            ->setRedirectParams(['id' => ':id']);

        $this->addActionButton(new Upgrade());
        $this->addActionButton($editProduct);
    }
    
    public function replaceFieldStats($key, $row)
    {
        $model          = new Hosting();
        $return         = null;
        foreach($this->stats as $color => $status)
        {
            $count = $model
                    ->join('tblservers' ,'tblhosting.server' , '=' , 'tblservers.id')
                    ->join('tblproducts' ,'tblhosting.packageid' , '=' , 'tblproducts.id')
                    ->where('tblhosting.domainstatus' ,'=', $status)
                    ->where('tblservers.type','=','directadminExtended')
                    ->where('tblproducts.name','=',$row->productName)
                    ->count();
            $label  = new Lab();
            $label->initIds('label' . $status)
                ->setTitle((string) $count)
                ->addClass('mg-cursor-default')
                ->setColor('e5fff4')
                ->setMessage($status)
                ->setBackgroundColor($color.';border-radius:20px;');

            $return .= $label->getHtml();
        }

        return $return;
    }
    public function replaceFieldServerType($key, $row)
    {
        return ServiceLocator::call('lang')->absoluteTranslate($row->{$key});
    }

    protected function loadData()
    {
        $query  = Whmcs\Product::query()
                    ->getQuery()
                    ->select([
                        'tblproducts.name as productName',
                        'tblproducts.id',
                        'tblproducts.serverType',
                        'tblproductgroups.name as groupName'
                    ])
                    ->join('tblproductgroups', 'tblproductgroups.id', '=', 'tblproducts.gid')
                    ->whereIn('serverType', $this->types);


        $dataProv   = new QueryDataProvider();
        $dataProv->setData($query);
        $dataProv->setDefaultSorting('id', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
