<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Feb 5, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\Providers\ArrayDataProvider;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of themeInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstalledDataTable extends RawDataTable  implements ClientArea
{
    protected $id    = 'mg-theme-installed';
    protected $name  = 'mg-theme-installed-name';
    protected $title = 'mg-theme-installed';

    public function isRawTitle()
    {
        return false;
    }
    protected function loadHtml()
    {
        $this->addColumn((new Column('title'))->setSearchable(true, Column::TYPE_STRING)->setOrderable(DataProvider::SORT_ASC))
                ->addColumn((new Column('description'))->setSearchable(true, Column::TYPE_STRING)->setOrderable())
                ->addColumn((new Column('version'))->setSearchable(true, Column::TYPE_STRING)->setOrderable())
                ->addColumn((new Column('status')));
                

    }

    public function initContent()
    {
        // activate/deactivate/update/delete
        $this->addMassActionButton(new UpdateMassButton('themeUpdateMassButton'));
        $this->addMassActionButton(new DeleteMassButton('themeDeleteMassButton'));
        $this->addActionButton(new ActivateButton('themeActivateButton'));
        $this->addActionButton(new UpdateButton('themeUpdateButton'));
        $this->addActionButton(new DeleteButton('themeDeleteButton'));
    }

    public function replaceFieldStatus($key, $row)
    {
        if (in_array($row['status'], [1, 'on', 'active']))
        {
            return sprintf('<span class="lu-label lu-label--success lu-label--status">%s</span>', sl('lang')->absoluteT('Enabled'));
        }
        return sprintf('<span class="lu-label lu-label--default lu-label--status">%s</span>', sl('lang')->absoluteT('Disabled'));
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
            if($installation->username){
                $module ->setUsername($installation->username);
            }
            $data=[];
            $res        = $module->getTheme($installation)->getList();
            foreach(  $res  as $d){
                $d['statusRaw'] =  $d['status'];
                $d['id'] =  base64_encode(json_encode($d));
                $data[]= $d;
            }
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

        $dataProv = new ArrayDataProvider();
        $dataProv->setDefaultSorting("title", 'asc');
        $dataProv->setData((array) $data);
        $this->setDataProvider($dataProv);
    }
}
