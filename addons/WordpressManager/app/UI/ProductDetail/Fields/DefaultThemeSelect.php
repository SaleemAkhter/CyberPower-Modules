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

use ModulesGarden\WordpressManager\App\Models\ThemePackage;
use ModulesGarden\WordpressManager\App\Models\ThemePackageItem;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;

/**
 * Description of TestInstallationSelect
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class DefaultThemeSelect extends Select implements AdminArea
{
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $options =[];
        $productSettingRepository               = new ProductSettingRepository();
        $productSetting                    = $productSettingRepository->forProductId($this->request->get('id'));
        //FW great idea
        if($this->request->get('autoInstallThemePackages' )){
            $autoInstallThemePackages = (array) explode(",", $this->request->get('autoInstallThemePackages' ));
        }else{
            $autoInstallThemePackages = (array)$productSetting->getAutoInstallThemePackages();
        }
        $tpi = (new ThemePackageItem())->getTable();
        $tp = (new ThemePackage())->getTable();
        $query = ThemePackageItem::select("{$tpi}.name", "{$tpi}.slug")
                                  ->leftJoin($tp,"{$tp}.id", "=","{$tpi}.theme_package_id")
                                  ->whereIn("{$tp}.id", $autoInstallThemePackages );
        foreach(   $query ->get() as $row){
            $options[] =[
                'key'    => $row->slug,
                'value' => htmlspecialchars_decode($row->name),
            ];
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue($productSetting->getDefaultTheme());
    }

}
