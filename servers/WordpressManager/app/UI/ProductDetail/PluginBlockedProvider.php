<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 26, 2018)
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

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\App\Models\PluginBlocked;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
/**
 * Description of PluginBlockProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginBlockedProvider extends BaseModelDataProvider implements AdminArea
{
    
    
    public function __construct()
    {
        parent::__construct(PluginBlocked::class);
    }
    
    public function create()
    {
        $dbData = $this->model->where('slug', $this->formData['slug'])
                              ->where('product_id', $this->formData['product_id'])->first();
        
        $this->formData['name'] = UtilityHelper::htmlEntityDecode($this->formData['name']);
        if ($dbData instanceof PluginBlocked)//update
        {
            $dbData->fill($this->formData)->save();
        }else{ //create
            $this->model->fill($this->formData)->save();
        }
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Blocked plugin :name: has been added successfully')
                                           ->setCallBackFunction('wpOnPluginBlockedCreatedAjaxDone');
    }
    
    public function createMass(){
        //Delete
        $productId = $this->request->get('id');
        foreach ($this->getRequestValue('massActions') as $record)
        {
            $data  = json_decode(base64_decode($record), true);
            $data['product_id']=$productId;
            $model = PluginBlocked::where('slug', $data['slug'])
                                    ->where('product_id', $productId)
                                    ->first();
            if (!$model instanceof PluginBlocked)//update
            {
                $model = new PluginBlocked();
            }
            $model->fill($data)->save();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugins have been blocked successfully')
                                           ->setCallBackFunction('wpOnPluginBlockedCreatedAjaxDone');
    }
    
    public function delete(){
        //Delete
        $this->model->where('id', $this->formData['id'])->delete();
         //Return Message
         sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Blocked plugin :name: has been deleted successfully');
    }
    
    public function deleteMass(){
        //Delete
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
         //Return Message
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected blocked plugins have been deleted successfully');
    }

    

}
