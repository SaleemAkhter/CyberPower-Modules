<?php

namespace ModulesGarden\Servers\VultrVps\Core\UI\Widget\CategoryMenu;

use ModulesGarden\Servers\VultrVps\Core\UI\Builder\BaseContainer;

/**
 * Container controler for category menu with dynemic content
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class CategoryMenuDynamicContent extends BaseContainer implements \ModulesGarden\Servers\VultrVps\Core\UI\Interfaces\AjaxElementInterface
{
    
    use \ModulesGarden\Servers\VultrVps\Core\UI\Traits\DatatableActionButtons;
    use \ModulesGarden\Servers\VultrVps\Core\UI\Traits\VSortable;
    
    protected $id                  = 'categoryMenuDynamicContent';
    protected $defaultTemplateName = 'categoryMenuDynamicContent';
    protected $searchable          = true;

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'category-menu';

    //todo add default returnAjaxData method for this menu
    public function returnAjaxData()
    {
        return (new ResponseTemplates\RawDataJsonResponse($this->elements))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
        //needs to be overwritten
        //use $this->getSearchKey(); to get search keyword
    }

    public function initContent()
    {
        //needs to be overwritten
    }
}
