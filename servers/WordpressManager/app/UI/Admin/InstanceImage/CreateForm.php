<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 22, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage;

use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\App\UI\Validators\DomainValidator;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use \ModulesGarden\WordpressManager\App\UI\Validators\NumberValidator;
use \ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage\Fields\Installation;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class CreateForm extends BaseForm implements AdminArea
{
    
    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->addClass('lu-row');
        $this->initIds('createForm');
        $this->setFormType('create');
        $this->setProvider(new InstanceImageProvider());
        $this->loadRequestObj();
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        
        $firstSection = new RawSection('firstSection');
        $firstSection->setMainContainer($this->mainContainer);
        //Enable 
        $firstSection->addField((new Fields\Switcher('enable')));
        //Custom
        $field = new Fields\Switcher('custom');
        $field ->setDescription('description');
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $firstSection->addField($field);
        $isCustom = $this->request->get('formData')['custom']=='on';
        if(!$isCustom){
            //Installation
            $field = new Installation('installation_id');
            $field->setDescription('description');
            $field->notEmpty();
            $firstSection->addField($field);
            //Private
            $firstSection->addField((new Fields\Switcher('private')));
        }
        //Name
        $firstSection->addField((new Fields\Text("name"))->notEmpty());
        if($isCustom){
            //Domain
            $firstSection->addField((new Fields\Text("domain"))->addValidator(new DomainValidator())->setPlaceholder('source.example.com')->setDescription('description'));
        }
        //Protocol
        $protocol = new Fields\Select("protocol");
        $protocol->setDescription('description');
        $protocol->setAvailableValues(['ftp' => "FTP", "ftps" => "FTPS", "sftp" => "SFTP"]);
        $firstSection->addField($protocol);
                
        $leftSides     = new HalfPageSection('leftSides');
        $rightSides    = new HalfPageSection('rightSides');
        //Server Host 
        $leftSides->addField((new Fields\Text("server_host"))->notEmpty()->setPlaceholder('ftp.source.example.com')->setDescription('description'));
        //Port
        $rightSides->addField((new Fields\Text("port"))->setDefaultValue(21)->addValidator(new NumberValidator('0', '65535', true))->setDescription('description')->setPlaceholder(21));
        $secoundSection = new RawSection('secoundSection');
        $secoundSection->setMainContainer($this->mainContainer);
        $secoundSection->addSection($leftSides)->addSection($rightSides);
        $leftSidet     = new HalfPageSection('leftSidet');
        $rightSidet    = new HalfPageSection('rightSidet');        
        //FTP User 
        $leftSidet->addField((new Fields\Text("ftp_user"))->notEmpty()->setDescription('description')->setPlaceholder('username'));
        //FTP Password 
        $rightSidet->addField((new Fields\Text("ftp_pass"))->notEmpty()->setDescription('description')->setPlaceholder('password'));
        $thirdSection= new RawSection('thirdSection');
        $thirdSection->setMainContainer($this->mainContainer);
        $thirdSection->addSection($leftSidet)->addSection($rightSidet);                        
        
        $firstSection->addField((new Fields\Text("ftp_path"))->notEmpty()->setDescription('description')->setPlaceholder('/public_html'));
        if($isCustom){
            //installed_path
            $firstSection->addField((new Fields\Text("installed_path"))->setDescription('description')->setPlaceholder('wp'));
        }
        $this->addSection($firstSection);
        $this->addSection($secoundSection);
        $this->addSection($thirdSection);
        $this->loadDataToForm();
    }
    
}
