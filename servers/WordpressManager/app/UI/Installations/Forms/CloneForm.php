<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 11, 2017)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use ModulesGarden\WordpressManager\App\Helper\Decorator;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class CloneForm extends BaseForm implements ClientArea
{
    use main\App\Http\Client\BaseClientController;

    protected function getDefaultActions()
    {
        return ['cloneAct'];
    }

    public function initContent()
    {
        $this->initIds('cloneForm');
        $this->setFormType('cloneAct');
        $this->setProvider( new InstallationProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];

        $this->setInternalAlertMessage('infoClone');
        $loggedinuser=array_pop(array_reverse($_SESSION['resellerloginas']));
        $this->reset()
            ->setHostingId($this->request->get('id'))
            ->setUserId($this->request->getSession('uid'));
        $this->subModule()->setUsername($loggedinuser);
        $domains=$this->subModule()->domain()->list();
        $domainlist=[];
        foreach ($domains as $key => $domain) {
            $domainlist[$domain]=$domain;
        }

        //softproto
        $field = new Fields\Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setValue([3]);
        $this->addField($field);
        //Domain

        $field = new Fields\Select('softdomain');
        $field->setAvailableValues( $domainlist);
        $field->setDescription('description');
        $field->notEmpty();
        $this->addField($field);
        //Directory
        $field = new Fields\Text('softdirectory');
        $field->setDescription('description')->setDefaultValue('wp');
        $this->addField($field);
        //db
        $dbName = "wp".UtilityHelper::generatePassword(3,'abcdefghijklmnopqrstuvwxyz123456789');

        $field = new Fields\Text('softdb');
        $field->setDescription('description')->setDefaultValue($dbName);;
        $field->notEmpty();
        $this->addField($field);
        //wpid
        $this->addField((new Fields\Hidden('wpid'))->setDefaultValue($wpId));
    }
    
}
