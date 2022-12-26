<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\CustomPlugin;

class CustomPluginSelect extends Select implements AdminArea
{
    protected $multiple = true;
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $selected = [];
        $options =[];
        $reposiotry               = new ProductSettingRepository;
        $model                    = $reposiotry->forProductId($this->request->get('id'));
        $customPlugins = (array) $model->getSettings()['customPlugins'];
        foreach(  CustomPlugin::select('name', 'id')->get() as $row){
            $options[] =[
                        'key'    => $row->id,
                        'value' => htmlspecialchars_decode($row->name),
                ];
            if(in_array($row->id,$customPlugins)){
                $selected[]= $row->id;
            }
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue($selected);
    }

}
