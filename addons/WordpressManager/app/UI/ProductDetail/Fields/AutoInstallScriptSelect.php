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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields;

use ModulesGarden\WordpressManager\App\Modules\Plesk;
use ModulesGarden\WordpressManager\App\UI\ProductDetail\InstallationScriptsSelect;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\App\Modules\Softaculous\CpanelProvider;
use \ModulesGarden\WordpressManager\App\Helper\LangException;
/**
 * Description of InstallationScriptsSelect
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class AutoInstallScriptSelect extends InstallationScriptsSelect implements AdminArea
{
    protected $multiple = false;
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $options =[];
        $reposiotry               = new ProductSettingRepository;
        $this->productSetting     = $reposiotry->forProductId($this->request->get('id'));
        foreach($this->getInstallationScripts() as $id => $app){
            $options[] =[
                        'key'    => $id,
                        'value' =>  sprintf("#%s %s", $id, $app['name']),
                ];
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue([$this->productSetting ->getAutoInstallScript()]);
    }

}
