<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackages;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of PluginPackageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PluginPackageProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct(main\App\Models\PluginPackage::class);
    }

    public function enableMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->update(["enable" =>1]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugin packages have been enabled successfully');
    }
    
    public function disableMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->update(["enable" =>0]);
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugin packages have been disabled successfully');
    }
    
    
    public function deleteMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected plugin packages have been deleted successfully');
    }
    
    public function delete()
    {
        parent::delete();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Plugin package :name: has been deleted successfully');
    }
}
