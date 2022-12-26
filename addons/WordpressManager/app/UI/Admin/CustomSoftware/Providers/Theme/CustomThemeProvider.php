<?php

namespace ModulesGarden\WordpressManager\App\UI\Admin\CustomSoftware\Providers\Theme;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Http\Admin\BaseAdminController;
use function\ModulesGarden\WordpressManager\Core\Helper\sl;

class CustomThemeProvider extends BaseModelDataProvider implements AdminArea
{
    use BaseAdminController;
    
    public function __construct()
    {
        parent::__construct(main\App\Models\CustomTheme::class);
    }

    public function read()
    {
        $this->data = $this->formData;
        if($this->formData){
            return;
        }
        parent::read();

        $enable = $this->data['enable'];
        $this->data['enable']= $enable==1 ? "on":"off" ;
    }

    public function create()
    {
        $enable = $this->formData['enable']=="on" ? 1:0;
        $this->formData['enable']= $enable ;

        $this->model->fill($this->formData)->save();

        sl('lang')->addReplacementConstant('name', $this->formData['name']);

        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Custom theme :name: has been created successfully');
    }
    
    public function update(){
        $this->formData['enable'] = $this->formData['enable']=="on" ? 1:0;

        parent::update();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);

        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Custom theme :name: has updated successfully');
    }

    public function deleteMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected custom themes have been deleted successfully');
    }
    
    public function delete()
    {
        parent::delete();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Custom theme :name: has been deleted successfully');
    }
}
