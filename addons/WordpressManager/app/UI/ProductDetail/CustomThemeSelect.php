<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\AjaxFields\Select;
use \ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\Models\CustomTheme;

class CustomThemeSelect extends Select implements AdminArea
{
    protected $multiple = true;
    
    public function prepareAjaxData()
    {
        $this->loadRequestObj();
        $selected = [];
        $options =[];
        $reposiotry               = new ProductSettingRepository;
        $model                    = $reposiotry->forProductId($this->request->get('id'));
        $customThemes = (array) $model->getSettings()['customThemes'];
        foreach(  CustomTheme::select('name', 'id')->get() as $row){
            $options[] =[
                        'key'    => $row->id,
                        'value' => htmlspecialchars_decode($row->name),
                ];
            if(in_array($row->id,$customThemes)){
                $selected[]= $row->id;
            }
        }
        $this->setAvailableValues($options);
        $this->setSelectedValue($selected);
    }

}
