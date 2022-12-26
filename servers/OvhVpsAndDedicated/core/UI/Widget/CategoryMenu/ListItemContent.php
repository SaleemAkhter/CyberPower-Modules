<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\CategoryMenu;

use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Widget\Tab;
use \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\ResponseTemplates;

/**
 * controler for Tab in DOE Category Page
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ListItemContent extends Tab implements \ModulesGarden\Servers\OvhVpsAndDedicated\Core\UI\Interfaces\AjaxElementInterface
{
    public $category = null;

    public function setCategory($category)
    {
        $this->category = $category;
    }
    protected $defaultTemplateName = 'ItemContent';

    public function returnAjaxData()
    {
        return (new ResponseTemplates\HtmlDataJsonResponse($this->getHtml()))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
    }

    public function initContent()
    {
        //needs to be overwritten
    }

    public function setId($id = null)
    {
        $this->id = 'menuItemContent' . (int) $id;
    }
}
