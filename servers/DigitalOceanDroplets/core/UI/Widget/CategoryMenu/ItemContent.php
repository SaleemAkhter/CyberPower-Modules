<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\CategoryMenu;

use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Widget\Tab;
use \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\ResponseTemplates;

/**
 * controler for Tab in DOE Category Page
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ItemContent extends Tab implements \ModulesGarden\Servers\DigitalOceanDroplets\Core\UI\Interfaces\AjaxElementInterface
{
    public $category = null;

    public function setCategory($category)
    {
        $this->category = $category;
    }
    protected $defaultTemplateName = 'ItemContent';

    public function returnAjaxData()
    {
        return (new ResponseTemplates\HtmlDataJsonResponse($this->getHtml()))->setCallBackFunction($this->callBackFunction);
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
