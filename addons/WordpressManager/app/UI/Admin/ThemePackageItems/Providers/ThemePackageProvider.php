<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 15, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\ThemePackageItems\Providers;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of PluginPackageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class ThemePackageProvider extends BaseModelDataProvider implements AdminArea
{

    public function __construct()
    {
        parent::__construct(main\App\Models\ThemePackage::class);
    }

    public function read()
    {
        $this->actionElementId                       = $this->getRequestValue('id');
        //options 
        $this->availableValues['testInstallationId'] = [];
        $h                                           = (new Hosting)->getTable();
        $i                                           = (new Installation)->getTable();
        $query                                       = (new Installation)->query()->getQuery()
                ->select("{$i}.*")
                ->leftJoin($h, "{$h}.id", "=", "{$i}.hosting_id")
                ->where("{$h}.domainstatus", "Active");
                
        foreach ($query->get() as $i)
        {
            $this->availableValues['testInstallationId'][$i->id] = sprintf("#%s %s", $i->id, $i->path);
        }
        $this->data['enable'] = 'on';
        
        parent::read();
        
        if ($this->data['id'])
        {
            $enable               = $this->data['enable'] == '1' ? 'on' : 'off';
            $this->data['enable'] = $enable;
        }
        //selected
        $this->data['testInstallationId'] = ['value' => (new ModuleSettings())->getTestInstallationId()];
    }

    public function create()
    {
        parent::create();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse(['id' => $this->model->id]))
                        ->setMessageAndTranslate('Theme package :name: has been added successfully')
                        ->setCallBackFunction('wpOnThemePackageCreatedAjaxDone');
    }

    public function update()
    {
        (new ModuleSettings())->setTestInstallationId($this->formData['testInstallationId']);
        $enable                   = $this->formData['enable'] == 'on' ? '1' : '0';
        $this->formData['enable'] = $enable;
        if (!$this->formData['id'])
        {
            return $this->create();
        }
        parent::update();
        sl('lang')->addReplacementConstant('name', $this->formData['name']);
        return (new HtmlDataJsonResponse(['id' => $this->model->id]))->setMessageAndTranslate('Theme package :name: has been updated successfully');
    }
}
