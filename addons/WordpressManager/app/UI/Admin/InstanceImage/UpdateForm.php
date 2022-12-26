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

namespace ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage;

use ModulesGarden\WordpressManager\App\UI\Validators\NumberValidator;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\App\UI\Validators\DomainValidator;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;
use \ModulesGarden\WordpressManager\App\UI\Admin\InstanceImage\Fields\Installation;
/**
 * Description of MassUpdateForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class UpdateForm extends BaseForm implements AdminArea
{

    public function getAllowedActions()
    {
        return ['update'];
    }

    public function initContent()
    {
        $this->initIds('updateForm');
        $this->addClass('lu-row');
        $this->setFormType('update');
        $this->setProvider(new InstanceImageProvider());
        $this->loadRequestObj();
        $this->initFields();
    }

    public function initFields()
    {
        
        $firstSection = new RawSection('firstSection');
        $firstSection->setMainContainer($this->mainContainer);
        $leftSide     = new HalfPageSection('leftSide');
        $rightSide    = new HalfPageSection('rightSide');
        $firstSection->addField(new Fields\Hidden('id'));
        //Enable 
        $firstSection->addField((new Fields\Switcher('enable')));
        //Custom
        $field = new Fields\Switcher('custom');
        $field ->setDescription('description');
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $firstSection->addField($field);
        $this->dataProvider->initData();
        $isCustom = $this->request->get('formData')['custom']=='on' ||  $this->dataProvider->getValueById('custom')=='on' ;
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
        //Domain 
        $firstSection->addField((new Fields\Text("domain"))->addValidator(new DomainValidator())->setPlaceholder('source.example.com')->setDescription('description'));
        //Protocol
        $protocol = new Fields\Select("protocol");
        $protocol->setDescription('description');
        $this->addField((new Fields\Text("domain"))->addValidator(new DomainValidator(false)));
        $protocol->setAvailableValues(['ftp' => "FTP", "ftps" => "FTPS", "sftp" => "SFTP"]);
        $firstSection->addField($protocol);
        //Server Host 
        $leftSide->addField((new Fields\Text("server_host"))->notEmpty()->setPlaceholder('ftp.source.example.com')->setDescription('description'));
        //Port
        $rightSide->addField((new Fields\Text("port"))->setDefaultValue(21)->addValidator(new NumberValidator('0', '65535', true))->setDescription('description')->setPlaceholder(21));
        //FTP User 
        $leftSide->addField((new Fields\Text("ftp_user"))->notEmpty()->setDescription('description')->setPlaceholder('username'));
        //FTP Password 
        $rightSide->addField((new Fields\Text("ftp_pass"))->notEmpty()->setDescription('description')->setPlaceholder('password'));
        $firstSection->addField((new Fields\Text("ftp_path"))->notEmpty()->notEmpty()->setDescription('description')->setPlaceholder('/public_html'));
        $firstSection->addField((new Fields\Text("installed_path"))->setDescription('description')->setPlaceholder('wp'));
        $this->addSection($firstSection);
        $this->addSection($leftSide);
        $this->addSection($rightSide);
        $this->loadDataToForm();
    }
}
