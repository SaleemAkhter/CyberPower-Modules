<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\App\Models\ThemeBlocked;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;

class ThemeBlockedProvider extends BaseModelDataProvider implements AdminArea
{
    
    
    public function __construct()
    {
        parent::__construct(ThemeBlocked::class);
    }
    
    public function create()
    {
        $dbData = $this->model->where('slug', $this->formData['slug'])
                              ->where('product_id', $this->formData['product_id'])->first();
        
        $this->formData['name'] = UtilityHelper::htmlEntityDecode($this->formData['name']);
        if ($dbData instanceof ThemeBlocked)//update
        {
            $dbData->fill($this->formData)->save();
        }else{ //create
            $this->model->fill($this->formData)->save();
        }
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Blocked theme :name: has been added successfully')
                                           ->setCallBackFunction('wpOnThemeBlockedCreatedAjaxDone');
    }
    
    public function createMass(){
        //Delete
        $productId = $this->request->get('id');
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $data['product_id']=$productId;
            $model = ThemeBlocked::where('slug', $data['slug'])
                                    ->where('product_id', $productId)
                                    ->first();
            if (!$model instanceof ThemeBlocked)//update
            {
                $model = new ThemeBlocked();
            }
            $model->fill($data)->save();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected themes have been blocked successfully')
                                           ->setCallBackFunction('wpOnThemeBlockedCreatedAjaxDone');
    }
    
    public function delete(){
        //Delete
        $this->model->where('id', $this->formData['id'])->delete();
         //Return Message
         sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Blocked theme :name: has been deleted successfully');
    }
    
    public function deleteMass(){
        //Delete
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected blocked themes have been deleted successfully');
    }

    

}
