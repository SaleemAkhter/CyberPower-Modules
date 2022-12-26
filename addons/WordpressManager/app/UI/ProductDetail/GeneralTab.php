<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 24, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\ProductDetail;

use ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields\AutoInstallInstanceImageSelect;
use ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields\AutoInstallPluginPackageSelect;
use ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields\AutoInstallScriptSelect;
use ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields\AutoInstallThemePackageSelect;
use ModulesGarden\WordpressManager\App\UI\ProductDetail\Fields\DefaultThemeSelect;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneFormExtSections;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Product;
use \ModulesGarden\WordpressManager\Core\Helper;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\App\UI\Validators\NumberValidator;
use \ModulesGarden\WordpressManager\App\Models\PluginPackage;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use \ModulesGarden\WordpressManager\App\Models\CustomTheme;
use \ModulesGarden\WordpressManager\App\Models\CustomPlugin;
/**
 * Description of GeneralTab
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class GeneralTab extends BaseStandaloneFormExtSections implements AdminArea
{
    protected $id    = 'generalTab';
    protected $name  = 'generalTab';
    protected $title = 'generalTabTitle';
    
     public function initContent()
    {
        $this->setFormType('update');
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $this->setProvider((new SettingProvider())->setProduct(Product::findOrFail($request->get('id'))));

        $baseSection = new GeneralSection();
        $baseSection->setMainContainer($this->mainContainer);
        $field = new Hidden();
        $field->setName('product_id');
        $field->setId('product_id');
        $baseSection->addField($field);
        $field = new Switcher('debugMode');
        $field->setDescription('Logs on Module Log');
        $baseSection->addField($field);
        //Installation Scripts
        $field = new InstallationScriptsSelect('installationScripts');
        $field->setDescription('description');
        $baseSection->addField($field);
        //Test Installation
        $field = new TestInstallationSelect('testInstallation');
        $field->setDescription('description');
        $baseSection->addField($field);
        //Prevent Installing plugins from blacklist.
        $field = new Switcher('pluginBlocked');
        $field->setDescription('description'); //Prevent Installing plugins from blacklist
        $baseSection->addField($field);
        //Automatically remove blacklisted applications
        $field = new Switcher('pluginBlockedDelete');
        $field->setDescription('description'); //Automatically remove plugin from blacklist
        $baseSection->addField($field);
        //Prevent Installing themes from blacklist.
        $field = new Switcher('themeBlocked');
        $field->setDescription('description');
        $baseSection->addField($field);
        //Automatically remove blacklisted applications
        $field = new Switcher('themeBlockedDelete');
        $field->setDescription('description'); //Automatically remove plugin from blacklist
        $baseSection->addField($field);
        //Scan Interal
        $field =(new Text())->addValidator(new NumberValidator(1,1000000000));
        $field->initIds('pluginScanInteral') ;
        $field->setDescription('description');//Scan Interal
        $baseSection->addField($field);
        //Plugin Package
        if(PluginPackage::count()>0){
            $baseSection->addField((new PluginPackageSelect('pluginPackages'))->setDescription('description'));
            //Auto Install Plugin Packages
            $baseSection->addField((new AutoInstallPluginPackageSelect('autoInstallPluginPackages'))->setDescription('description'));
        }
        if(CustomPlugin::count())
        {
            $baseSection->addField((new CustomPluginSelect('customPlugins'))->setDescription('description'));
        }
        //Auto Install Theme Packages
        $baseSection->addField((new AutoInstallThemePackageSelect('autoInstallThemePackages'))->setDescription('description'));
        //Default Theme
        $field = (new DefaultThemeSelect('defaultTheme'))
                  ->setDescription('description')
                 ->addReloadOnChangeField('autoInstallThemePackages');
        $baseSection->addField($field);

        if(CustomTheme::count())
        {
            $baseSection->addField((new CustomThemeSelect('customThemes'))->setDescription('description'));
        }
        //InstanceImage
        if(InstanceImage::count())
        {
            $baseSection->addField((new InstanceImageSelect('instanceImages'))->setDescription('description'));
        }
        //Auto install
        $field = new Select('autoInstall');
        $field->setDefaultValue("0");
        $field->setDescription('description');
        $baseSection->addField($field);
        //Auto Install Script
        $field = new AutoInstallScriptSelect('autoInstallScript');
        $field->setDescription('description');
        $baseSection->addField($field);
        //Auto Install Instance Image
        if(InstanceImage::count()){
            $baseSection->addField((new AutoInstallInstanceImageSelect('autoInstallInstanceImage'))->setDescription('description'));
        }
        //deleteAutoInstall
        $field = new Switcher('deleteAutoInstall');
        $field->setDescription('description');
        $field->setDefaultValue('on');
        $baseSection->addField($field);
        //Auto Install Protocol
        $field = new Select('autoInstallSoftProto');
        $field->setDefaultValue(3);
        $field->setDescription('description');
        $baseSection->addField($field);
        //Auto Install Email Template
        $field = new Select('autoInstallEmailTemplate');
        $field->setDescription('description');
        $baseSection->addField($field);

        $field =(new Text())->addValidator(new NumberValidator(-1000000000,1000000000));
        $field->initIds('installationsLimit') ;
        $field->setDescription('description');
        $baseSection->addField($field);

        $field = new Switcher('updateWpVersionNotifications');
        $field->setDescription('description');
        $baseSection->addField($field);

        //Send Update WordPress Notification Mail Interval
        $field =(new Text())->addValidator(new NumberValidator(1,1000000000));
        $field->initIds('updateWpVersionNotificationInterval') ;
        $field->setDescription('description');//Mail Interval
        $baseSection->addField($field);

        //Send Update WordPress Notification Mail Template
        $field = new Select('updateWpVersionNotificationTemplate');
        $field->setDescription('description');
        $baseSection->addField($field);

        $this->addSection( $baseSection);
        $this->loadDataToForm();
     }
}
