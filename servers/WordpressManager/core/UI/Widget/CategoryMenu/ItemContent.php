<?php

namespace ModulesGarden\WordpressManager\Core\UI\Widget\CategoryMenu;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Tab;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

/**
 * controler for Tab in DOE Category Page
 *
 * @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
 */
class ItemContent extends Tab implements \ModulesGarden\WordpressManager\Core\UI\Interfaces\AjaxElementInterface
{
    public $category = null;

    public function setCategory($category)
    {
        $this->category = $category;
    }
    protected $defaultTemplateName = 'ItemContent';

    public function returnAjaxData()
    {
        $returnHtml = $this->getHtml();
        $returnTemplate = self::getVueComponents();

        return (new ResponseTemplates\RawDataJsonResponse(['htmlData' => $returnHtml, 'template' => $returnTemplate,
            'registrations' => self::getVueComponentsRegistrations()]))->setCallBackFunction($this->callBackFunction)->setRefreshTargetIds($this->refreshActionIds);
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
