<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 31, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\RawDataJsonResponse;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\PluginPackage;
/**
 * Description of TestInstallationSelect
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginPackageSelect extends Select implements AdminArea
{
    protected $multiple = true;
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $selected = [];
        $options =[];
        $reposiotry               = new ProductSettingRepository;
        $model                    = $reposiotry->forProductId($this->request->get('id'));
        $pluginPackages = (array) $model->getSettings()['pluginPackages'];
        foreach(  PluginPackage::select('name', 'id')->get() as $row){
            $options[] =[
                        'key'    => $row->id,
                        'value' => htmlspecialchars_decode($row->name),
                ];
            if(in_array($row->id,$pluginPackages)){
                $selected[]= $row->id;
            }
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue($selected);
    }

}
