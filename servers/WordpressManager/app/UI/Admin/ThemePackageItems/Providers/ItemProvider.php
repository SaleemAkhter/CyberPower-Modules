<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 15, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Providers;

use ModulesGarden\WordpressManager\App\Models\ThemePackageItem;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use \ModulesGarden\WordpressManager\App\Models\PluginPackageItem;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of ItemProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ItemProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct(ThemePackageItem::class);
    }

    public function create()
    {
        $data['theme_package_id'] = $this->getRequestValue('id');
        $dbData                    = $this->model->where('slug', $this->formData['slug'])
                                                 ->where('theme_package_id', $this->formData['theme_package_id'])
                                                 ->first();
        $this->formData['name'] = UtilityHelper::htmlEntityDecode($this->formData['name']);
        if ($dbData instanceof ThemePackageItem)//update
        {
            $dbData->fill($this->formData)->save();
        }
        else
        { //create
            $this->model->fill($this->formData)->save();
        }
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Theme :name: has been added successfully')
                        ->setCallBackFunction('wpOnThemePackageItemCreated');
    }

    public function createMass()
    {
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data                      = json_decode(base64_decode($record), true);
            $data['theme_package_id'] = $this->getRequestValue('id');
            $model                     = ThemePackageItem::where('slug', $data['slug'])
                    ->where('theme_package_id', $data['theme_package_id'])
                    ->first();
            if (!$model instanceof ThemePackageItem)//update
            {
                $model = new ThemePackageItem();
            }
            $model->fill($data)->save();
        }
        //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been added successfully')
                        ->setCallBackFunction('wpOnThemePackageItemCreated');
    }

    public function delete(){
        //Delete
        $this->model->where('id', $this->formData['id'])->delete();
         //Return Message
         sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Theme :name: has been deleted successfully');
    }
    
    public function deleteMass(){
        //Delete
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been deleted successfully');
    }
}
