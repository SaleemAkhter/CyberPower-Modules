<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 18, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Client\InstallationDetails;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\UI\Validators\NumberValidator;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use  ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\RawSection;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Sections\HalfPageSection;

/**
 * Description of InstanceImageForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['create','update'];
    }
    

    public function initContent()
    {
        $this->initIds('instanceImageForm');
        $this->addClass('lu-row');
        $this->setFormType('create');
        $this->loadRequestObj();
        if(InstanceImage::OfUserId($this->request->getSession('uid'))->ofInstallationId($this->request->get('wpid'))->count()){
            $this->setFormType('update');
        }
        $this->setProvider( new InstanceImageProvider());
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['wpid'];

        //Content
        $content = new RawSection();
        $content->setMainContainer($this->mainContainer);
        //Top
        $topSection = new RawSection('topSection');
        $topSection ->setMainContainer($this->mainContainer);
        //Left
        $leftSection  = new HalfPageSection('leftSection');
        $leftSection->setMainContainer($this->mainContainer);
        //Right
        $rightSection = new HalfPageSection('rightSection');
        $rightSection->setMainContainer($this->mainContainer);
        //Bottom
        $bottomSection = new RawSection('bottomSection');
        $bottomSection->setMainContainer($this->mainContainer);
        //Add sections
        $content->addSection($topSection);
        $content->addSection($leftSection);
        $content->addSection($rightSection);
        $content->addSection($bottomSection);
        $this->addSection($content);
        //Mark as Image
        $markAsImage = (new Fields\Switcher("markAsImage"))->setDefaultValue('on');
        $markAsImage->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $topSection->addField($markAsImage);
        //wpid
        $topSection->addField((new Fields\Hidden('wpid'))->setDefaultValue($wpId));

        if($this->request->get('formData')['markAsImage'] && $this->request->get('formData')['markAsImage']=='off'){
            $this->setInternalAlertMessage('infoInstanceImageDisable', 'info', '');
            return;
        }
        $this->setInternalAlertMessage('infoInstanceImageEnable', 'info', '');
        //Name
        $topSection->addField((new Fields\Text("name"))->notEmpty());
        //Protocol
        $protocol = new Fields\Select("protocol");
        $protocol->setAvailableValues(['ftp' => "FTP", "ftps" => "FTPS", "sftp" => "SFTP"]);
        $protocol->setDescription('description');
        $topSection->addField($protocol);
        //Server Host 
        $leftSection->addField((new Fields\Text("server_host"))->notEmpty()->setDescription('description')->setPlaceholder('ftp.source.example.com'));
        //Port
        $rightSection->addField((new Fields\Text("port"))->setDefaultValue(21)->addValidator(new NumberValidator('0', '65535', true))->setDescription('description')->setPlaceholder(21));
        //FTP User 
        $leftSection->addField((new Fields\Text("ftp_user"))->notEmpty()->setDescription('description')->setPlaceholder('username'));
        //FTP Password 
        $rightSection->addField((new Fields\Text("ftp_pass"))->notEmpty()->setDescription('description')->setPlaceholder('password'));
        //FTP Path
        $bottomSection->addField((new Fields\Text("ftp_path"))->notEmpty()->setDescription('description')->setPlaceholder('/public_html'));
    }
}
