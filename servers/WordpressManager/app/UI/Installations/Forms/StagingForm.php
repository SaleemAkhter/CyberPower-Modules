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

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Repositories\HostingRepository;
use ModulesGarden\WordpressManager\App\Helper\Decorator;
use ModulesGarden\WordpressManager\App\UI\Validators\DomainValidator;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use \ModulesGarden\WordpressManager\Core\UI\Helpers\AlertTypesConstants;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Text;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\StagingProvider;
use ModulesGarden\WordpressManager\App\Helper\InstallationLimit;
use ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
/**
 * Description of InstallationStagingForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class StagingForm extends BaseForm implements ClientArea
{
    use BaseClientController;

    protected $modal;

    /**
     * InstallationCreateForm constructor.
     * @param main\App\UI\Installations\Modals\StagingModal $modal
     */
    public function __construct(main\App\UI\Installations\Modals\StagingModal $modal)
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
        $this->initIds('stagingForm');
        $this->setFormType('create');
        if($this->isLimitReached())
        {
            $this->modal->removeActionButtonByIndex('baseAcceptButton');
            return;
        }
        $this->setProvider(new StagingProvider);
        $this->initFields();
    }

    private function isLimitReached()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
        if(InstallationLimit::stageCheckLimit($wpId))
        {
            $this->setConfirmMessage('allHostingsLimited', ['title' => null]);
            return true;
        }

        return false;
    }

    private function initFields(){
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
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

        $softproto = new Select('softproto');
        $softproto->setAvailableValues((new Decorator())->getProtocols());
        $softproto->setDefaultValue('3');
        $this->addField($softproto);
        $inputInstallationId = new Hidden('installation_id');
        $this->addField($inputInstallationId);
        $url = new Hidden('url');
        $this->addField($url);
        $softdomain = new Select('softdomain');
        $softdomain->setAvailableValues( $domainlist);
        $softdomain->setDescription('description');
        $softdomain->addValidator(new DomainValidator());
        $this->addField($softdomain );
        $softdirectory = new Text('softdirectory');
        $softdirectory->setDefaultValue('wp');
        $softdirectory->setDescription('description');
        $this->addField($softdirectory );
        $softdb = new Text('softdb');
        $softdb->setDescription('description');
        $this->addField($softdb );
        $this->setInternalAlertMessage('installationConfirmStaging', AlertTypesConstants::INFO, AlertTypesConstants::DEFAULT_SIZE)
             ->addLocalLangReplacements(["url" => null]);
        $this->loadDataToForm();
      
    }
}
