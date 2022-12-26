<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 7, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\Core\ModuleConstants;
use \ModulesGarden\WordpressManager\App\Models\ProductSetting;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use \ModulesGarden\WordpressManager\App\Helper\CheckWpVersion;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonBase;
use ModulesGarden\WordpressManager\App\UI\Client\Customs\Pages\CollapsableTable;
/* *
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class Installations extends CollapsableTable implements ClientArea
{

    use RequestObjectHandler;
    use main\App\Http\Client\BaseClientController;

    protected $id    = 'labelscont';
    protected $name  = 'labelscont';
    protected $title = 'labelsContTitle';


    protected function loadHtml()
    {
        $this->loadRequestObj();
        if($this->request->get('hostingId')){
            $this->setUserId($this->request->getSession('uid'));
            $this->setHostingId($this->request->get('hostingId'));
            Helper\sl('lang')->addReplacementConstant('productName', $this->getHosting()->product->name);
            Helper\sl('lang')->addReplacementConstant('domain', $this->getHosting()->domain);
            $this->title ='WordPress Installations for :productName: :domain:';
        }
        $i        = (new Installation)->getTable();
        $p        = (new Product())->getTable();
        $h        = (new Hosting())->getTable();
        $this->addColumn((new Column('domain', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable(providers\DataProvider::SORT_ASC)
        )->addColumn((new Column('name', $p))
                     ->setSearchable(true, Column::TYPE_STRING)
                      ->setOrderable());
        if($this->isRessellerHosting()){
            $this->addColumn((new Column('username', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable());
        }
        $this->addColumn((new Column('url', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable()
        )->addColumn((new Column('version', $i))
                        ->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable()
        )->addColumn((new Column('created_at', $i))
                        ->setSearchable(true, Column::TYPE_DATE)
                        ->setOrderable()
        );
    }

    public function initContent()
    {
        $this->addButton(new Buttons\InstallationCreateButton());
        $this->addButton(new Buttons\ImportButton('importButton'));
        $hasPrivateInstanceImage = InstanceImage::OfUserId($this->userId)->enable()->count() > 0;
        if ((ProductSetting::enable()->where('settings','like', '%instanceImages%')->count()   && InstanceImage::enable()->where('id', '>', 0)->count() > 0 )
             || $hasPrivateInstanceImage  )
        {
            $this->addButton(new Buttons\InstanceImageButton('instanceImageButton'));
        }
        $request  = Helper\sl('request');
        //control panel
        $cp = new ButtonRedirect('controlPanelButton');
        $cp->addHtmlAttribute('target', '_blank');
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
            'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=controlPanel'
            : BuildUrl::getUrl('home', 'controlPanel');
        $cp->setRawUrl($baseUrl)
            ->setRedirectParams(['wpid' => ':id']);
        $cp->setIcon('lu-zmdi lu-zmdi-shield-security');
        $this->addActionButton($cp);
        $rd = new ButtonRedirect;
        $baseUrl = $request->get('action', false) === 'productdetails' && $request->get('mg-page', false) === 'wordPressManager' ?
                'clientarea.php?action=productdetails&id=' . $request->get('id', '') . '&modop=custom&a=management&mg-page=wordPressManager&mg-action=details'
                : BuildUrl::getUrl('home', 'detail');
        $rd->setRawUrl($baseUrl)
                ->setRedirectParams(['wpid' => ':id']);
        $rd->setIcon('lu-zmdi lu-zmdi-edit');
        $this->addActionButton($rd);
        $this->addActionButton(new Buttons\InstallationDeleteActionButton());
        $this->addMassActionButton(new main\App\UI\Installations\Buttons\InstallationMassUpgradeButton());
        $this->addMassActionButton(new main\App\UI\Installations\Buttons\InstallationMassDeleteButton());
    }

    public function replaceFieldDomain($key, $row)
    {
        return sprintf('<a href="%s" target="_blank">%s</a>', $row->url, $row->domain);
    }

    public function replaceFieldUrl($key, $row)
    {
        return sprintf('<a href="%s" target="blank">%s</a>', $row->url, $row->url);
    }

    public function replaceFieldCreated_at($key, $row)
    {
        return WhmcsHelper::fromMySQLDate($row->$key, true);
    }

    public function replaceFieldName($key, $row)
    {
        /* @var $hosting main\App\Models\Whmcs\Hosting  */
        $hosting = main\App\Models\Whmcs\Hosting::find($row->hosting_id);
        return sprintf('<a href="clientarea.php?action=productdetails&id=%s">%s</a>', $row->hosting_id, $hosting->product->name);
    }

    public function replaceFieldUsername($key, $row)
    {
        return $row->username ? $row->username : '-';
    }

    public function replaceFieldAuto($key, $row)
    {
        if( $row->auto==1 && ProductSetting::ofProductId($row->packageid)->where('settings','like', '%"deleteAutoInstall":1%')->count()  ){
            $row->auto =0;
        }
        return $row->auto ;
    }

    public function replaceFieldVersion($key, $row)
    {
        $result = (new CheckWpVersion())->checkIfNewer($row->version);

        if($result)
        {
            $rd = new ButtonBase;
            $rd->setName('updateWpAlert');
            $rd->setTitle('updateWpAlert');
            $rd->replaceClasses(['lu-btn lu-btn--sm lu-btn--link lu-btn--icon lu-btn--danger redAlert vertical-align-middle']);
            $rd->setIcon('lu-btn__icon lu-zmdi lu-zmdi-alert-circle');

            return $row->version.$rd;
        }

        return $row->version;
    }

    protected function loadData()
    {
        $this->loadRequestObj();
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request  = Helper\sl('request');
        $i        = (new Installation)->getTable();
        $p        = (new Product())->getTable();
        $h        = (new Hosting())->getTable();
        $query    = (new Installation)
                ->query()
                ->getQuery()
                ->rightJoin($h, "{$i}.hosting_id", '=',"{$h}.id")
                ->rightJoin($p, "{$h}.packageid", '=',"{$p}.id")
                ->select("{$i}.id", "{$i}.domain", "{$i}.hosting_id", "{$i}.url", "{$i}.path", "{$i}.version", "{$i}.staging", "{$i}.created_at", "{$i}.username",
                    "{$i}.auto", "{$p}.name", "{$h}.packageid")
                ->where("{$i}.user_id", $request->getSession('uid'))
                ->where("{$h}.domainstatus","Active");

        if($this->request->get('hostingId')){
            $query->where("{$i}.hosting_id",$this->request->get('hostingId'));
        }

        $dataProv = new providers\Providers\QueryDataProvider();
        $dataProv->setDefaultSorting("domain", 'ASC');
        $dataProv->setData($query);
        $this->setDataProvider($dataProv);
    }

    private function isRessellerHosting(){
        $this->loadRequestObj();
        return Hosting::ofUserId( $this->request->getSession('uid'))->ProductReseller()->count()>0;
    }

    protected function getReplacementFunctions()
    {
        $replacementFunctions = parent::getReplacementFunctions();
        $replacementFunctions['auto'] = 'replaceFieldAuto';
        return $replacementFunctions;
    }


}
