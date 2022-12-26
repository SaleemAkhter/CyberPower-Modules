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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackageItems;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use \ModulesGarden\WordpressManager\App\Models\PluginPackageItem;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager as main;

/**
 * Description of ItemProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ItemProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct(main\App\Models\PluginPackageItem::class);
    }

    public function create()
    {
        $data['plugin_package_id'] = $this->getRequestValue('id');
        $dbData                    = $this->model->where('slug', $this->formData['slug'])
                                                 ->where('plugin_package_id', $this->formData['plugin_package_id'])
                                                 ->first();
        $this->formData['name'] = UtilityHelper::htmlEntityDecode($this->formData['name']);
        if ($dbData instanceof PluginPackageItem)//update
        {
            $dbData->fill($this->formData)->save();
        }
        else
        { //create
            $this->model->fill($this->formData)->save();
        }
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Plugin :name: has been added successfully')
                        ->setCallBackFunction('wpOnPluginPackageItemCreated');
    }

    public function createMass()
    {
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data                      = json_decode(base64_decode($record), true);
            $data['plugin_package_id'] = $this->getRequestValue('id');
            $model                     = PluginPackageItem::where('slug', $data['slug'])
                    ->where('plugin_package_id', $data['plugin_package_id'])
                    ->first();
            if (!$model instanceof PluginPackageItem)//update
            {
                $model = new PluginPackageItem();
            }
            $model->fill($data)->save();
        }
        //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugins have been added successfully')
                        ->setCallBackFunction('wpOnPluginPackageItemCreated');
    }

    public function delete(){
        //Delete
        $this->model->where('id', $this->formData['id'])->delete();
         //Return Message
         sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Plugin :name: has been deleted successfully');
    }
    
    public function deleteMass(){
        //Delete
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugins have been deleted successfully');
    }
}
