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

namespace ModulesGarden\WordpressManager\App\UI\Installations;

use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\Column;
use \ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders\DataProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\DataTable\DataProviders as providers;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\RawDataTable\RawDataTable;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;

/**
 * Description of PluginInstalled
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginInstalled extends RawDataTable  implements ClientArea
{
    protected $id = 'mg-plugins-installed';
    protected $name = 'mg-plugins-installed-name';
    protected $title = 'mg-plugins-installed';
    protected $sort =['status'];
    public function isRawTitle()
    {
        return false;
    }

    protected function loadHtml()
    {

        $this->addColumn((new Column('title'))->setSearchable(true, Column::TYPE_STRING)->setOrderable())
            ->addColumn((new Column('version'))->setSearchable(true, Column::TYPE_STRING)->setOrderable())
            ->addColumn((new Column('status'))->setOrderable(DataProvider::SORT_DESC));
    }

    public function replaceFieldTitle($key, $row){

        if($row[$key]){
            return $row[$key];
        }
        return $row['name'];
    }

    public function replaceFieldVersion($key, $row){
        if($row[$key]){
            if($row['update']=='available'){
                return $row[$key]. ' <span class="updatebtncontainer"></span>';
            }else{
                return $row[$key];
            }

        }
        return '-';
    }

    public function initContent()
    {
        // activate/deactivate/update
        $this->addMassActionButton(new Buttons\PluginActivateMassButton('pluginActivateMassButton'));
        $this->addMassActionButton(new Buttons\PluginDeactivateMassButton('pluginDeactivateMassButton'));
        $this->addMassActionButton(new Buttons\PluginUpdateMassButton('pluginUpdateMassButton'));
        $this->addMassActionButton(new Buttons\PluginDeleteMassButton('pluginDeleteMassButton'));
        $this->addActionButton(new Buttons\PluginActivateButton('pluginActivateButton'));
        $this->addActionButton(new Buttons\PluginDeactivateButton('pluginDeactivateButton'));
        $this->addActionButton(new Buttons\PluginUpdateButton('pluginUpdateButton'));
        $this->addActionButton(new Buttons\PluginDeleteButton('pluginDeleteButton'));
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
            $data         = $module->getPlugins($installation);
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

        foreach ($data as $key => $row) {
            $data[$key]['statusRaw']=$row['status'];
        }
        $dataProv = new providers\Providers\ArrayDataProvider();

        $dataProv->setDefaultSorting("status", 'asc');
        $dataProv->setData((array) $data);

        $this->setDataProvider($dataProv);
    }
}
