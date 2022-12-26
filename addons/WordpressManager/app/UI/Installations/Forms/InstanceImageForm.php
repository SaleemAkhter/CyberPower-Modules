<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 5, 2018)
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
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstanceImageModal;
use ModulesGarden\WordpressManager\Core\UI\Helpers\AlertTypesConstants;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstanceImageProvider;
use \ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
/**
 * Description of InstallationStagingForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageForm extends BaseForm implements ClientArea
{
    use BaseClientController;

    protected $formData;

    /**
     * @var main\App\UI\Installations\Modals\InstanceImageModal
     */
    protected $modal;

    public function __construct(InstanceImageModal $modal)
    {
        $this->modal    = $modal;
        parent::__construct();
    }

    protected function getDefaultActions()
    {
        return ['create'];
    }


    public function initContent()
    {
        $this->initIds('instanceImageForm');
        $this->setFormType('create');
        $this->setProvider(new InstanceImageProvider);
        $this->loadRequestObj();
        $this->formData = $this->request->get('formData');
        $this->setInternalAlertMessage('info');
        $this->initFields();
        $this->loadDataToForm();
    }

    private function initFields()
    {
        //hostingId
        $repository = new HostingRepository;
        $hostings = $repository->findEnabledWithProduct($this->request->getSession('uid'));
        if (!$this->formData['hostingId'])
        {
            $this->formData['hostingId'] = key($hostings);
        }
        $this->reset()
            ->setHostingId($this->formData['hostingId'])
            ->setUserId($this->request->getSession('uid'));      
              
        $hasPrivateInstanceImage = InstanceImage::OfUserId($this->userId)->enable()->count() > 0;
        if (count($hostings ) > 1)
        {
            $field  = new Select('hostingId');
            $field->initIds('hostingId');
            $field->setDescription('description');
            $options = [];
            
            foreach(Hosting::ofUserId($this->userId)->active()->productEnable()->get() as $hosting)
            {
                /*@var $hosting Hosting */
                if(!$hasPrivateInstanceImage && !$hosting->productSettings->hasInstanceImage())
                {
                    continue;
                }
                $options[$hosting->id] = sprintf("%s - %s",$hosting->product->name , $hosting->domain);
            }
            $field->setAvailableValues($options);
            $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
            $this->addField($field);
        }
        else if (count($hostings ) == 1)
        {
            $field  = new Hidden('hostingId');
            $field->setDefaultValue($this->formData['hostingId']);
            $this->addField($field);
        }
         //username
        if($this->getHosting()->product->isTypeReseller()){
            $field = new Select('username');
            $field->notEmpty();
            $options=[];
            foreach($this->subModule()->reseller()->getAccounts() as $account){
                $options[$account['username']]=$account['username'];
            }
            if(empty($options)){
                $this->addInternalAlert('no_users_under_reseller_account', AlertTypesConstants::DANGER);
                $this->modal->removeActionButtonByIndex('baseAcceptButton');
                return;
            }
            $field->setAvailableValues($options);
            $this->addField($field);
        }
        //instanceImageId
        $field = new Select('instanceImageId');
        $enteries = InstanceImage::ofId($this->getHosting()->productSettings->getInstanceImages())
                              ->enable()
                              ->pluck("name","id");
        $field->setAvailableValues(($enteries ? $enteries->all() : []));   
        $privete = InstanceImage::OfUserId($this->request->getSession('uid'))
                                  ->enable()
                                  ->pluck("name","id");
        if($privete ){
           $options = (array)  $field->getAvailableValues() + (array) $privete->all();
           $field->setAvailableValues($options);
        }
        $field->setDescription('description');
        $this->addField($field);
        //softproto
        $field = new Select('softproto');
        $field->setAvailableValues((new Decorator())->getProtocols());
        $field->setDefaultValue([3]);
        $this->addField($field);
        //softdomain
        $this->addField((new Text('softdomain'))->notEmpty()->setDescription('description')->setPlaceholder('destination.example.com'));
        //dest_directory
        $this->addField((new Text('dest_directory'))->setDescription('description')->setPlaceholder('wp'));
        //softdb 
        $this->addField((new Text('softdb'))->notEmpty()->setDescription('description')->setPlaceholder('name'));
    }
}
