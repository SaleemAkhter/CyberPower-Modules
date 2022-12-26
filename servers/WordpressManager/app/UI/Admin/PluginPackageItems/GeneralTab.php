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

namespace ModulesGarden\WordpressManager\App\UI\Admin\PluginPackageItems;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseStandaloneFormExtSections;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Textarea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
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
        $this->setProvider(new PluginPackageProvider);
        $general = new GeneralSection();
        $general->setMainContainer($this->mainContainer);
        //id
        $general->addField(new Hidden('id'));
        //enable
        $general->addField((new Switcher('enable'))->setDescription('description')->setDefaultValue("on"));
        //name
        $general->addField((new Text('name'))->notEmpty());
        //testInstallationId 
        $general->addField((new Select('testInstallationId'))->setDescription('description')->notEmpty());
        //description
        $general->addField((new Textarea('description')));
        //enable
        $general->addField((new Switcher('enable'))->setDefaultValue('on'));
        $this->addSection( $general);
        $this->loadDataToForm();
     }
     
}
