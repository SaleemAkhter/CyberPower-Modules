<?php

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneFormExtSections;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;

class ClientOptionsTab extends BaseStandaloneFormExtSections implements AdminArea
{
    protected $id    = 'clientOptionsTab';
    protected $name  = 'clientOptionsTab';
    protected $title = 'clientOptionsTitle';
    
     public function initContent()
    {
        $this->setFormType('update');
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $this->setProvider((new ClientSettingProvider())->setProduct(Product::findOrFail($request->get('id'))));
        $baseSection = new GeneralSection();
        $baseSection->setMainContainer($this->mainContainer);

        $managementSection = new RawSection('management-section');
        $field = new Hidden();
        $field->setName('product_id');
        $field->setId('product_id');
        $managementSection->addField($field);

/*         $field = new Switcher('management-installation-details');
        $field->setDescription('description');
        $managementSection->addField($field); */

        $field = new Switcher('pageSpeedInsightsOption');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-backups');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-themes');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-plugins');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-plugin-packages');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-config');
        $field->setDescription('description');
        $managementSection->addField($field);

        $field = new Switcher('management-users');
        $field->setDescription('description');
        $managementSection->addField($field);

        $actionsSection = new RawSection('actions-section');

        $field = new Switcher('actions-control-panel');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-clear-cache');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-clone');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-update');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-change-domain');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-manage-auto-upgrade');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-staging');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-push-to-live');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-ssl');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-maintenance-mode');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $field = new Switcher('actions-instance-image');
        $field->setDescription('description');
        $actionsSection->addField($field);

        $baseSection->addSection($managementSection);
        $baseSection->addSection($actionsSection);

        $this->addSection($baseSection);
        $this->loadDataToForm();
     }
}
