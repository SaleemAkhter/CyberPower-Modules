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
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\WhmcsHelper;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ButtonRedirect;
use ModulesGarden\WordpressManager\Core\Helper\BuildUrl;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

/* * gb
 * Description of InstallationPage
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */

class BackupPage extends DataTable implements ClientArea
{
    protected $id    = 'mg-backups';
    protected $name  = 'mg-backups-name';
    protected $title = 'mg-backups-title';

    protected function loadHtml()
    {
        $this->addColumn((new Column('name'))->setSearchable(true, Column::TYPE_STRING)
                        ->setOrderable()
                )
                ->addColumn((new Column('size'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('version'))->setSearchable(true)->setOrderable())
                ->addColumn((new Column('date'))->setSearchable(true)->setOrderable(DataProvider::SORT_DESC))
                ->addColumn((new Column('note'))->setSearchable(true));

    }

    public function initContent()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        //Sidebar
        Helper\sl('sidebar')->add( new Sidebars\Actions('actions'));   
        $this->addMassActionButton(new Buttons\BackupDeleteMassButton);
        //Create Backup
        $this->addButton(new Buttons\BackupCreateButton);
        $rd      = new ButtonRedirect;
        $rd->setIcon('lu-zmdi lu-zmdi-download');
        $rd->setRawUrl(BuildUrl::getUrl('home', 'backupDownload'))
                ->setRedirectParams([
                    'wpid'       => $request->get('wpid'),
                    'backupId' => ':id']);
        $this->addActionButton($rd);
        $this->addActionButton(new Buttons\BackupRestoreButton('backupRestoreButton'));
        $this->addActionButton(new Buttons\BackupDeleteButton('backupDeleteButton'));
    }

    protected function loadData()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        try
        {
            /* @var  $installation  Installation */
            $installation = Installation::where('id', $request->get('wpid'))
                    ->where('user_id', $request->getSession('uid'))
                    ->firstOrFail();
            $module       = Wp::subModule($installation->hosting);
            if( $installation->username){
                $module->setUsername($installation->username);
            }
            $data         = $module->getBackups($installation->relation_id);
        }
        catch (\Exception $ex)
        {
            // Record the error in WHMCS's module log.
            logModuleCall(
                'WordpressManager',
                __FUNCTION__,
                $ex->getMessage(),
                $ex->getTraceAsString()
            );
            throw $ex;
        }

        $dataProv = new providers\Providers\ArrayDataProvider();
        $dataProv->setDefaultSorting("date", 'desc');
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
