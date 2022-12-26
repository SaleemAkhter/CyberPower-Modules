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

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\Core\UI\Helpers\AlertTypesConstants;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\UpgradeProvider;
use \ModulesGarden\WordpressManager\App\UI\Installations\Modals\InstallationUpgradeModal;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InstallationUpgradeForm extends BaseForm implements ClientArea
{
    use BaseClientController;
    /**
     *
     * @var InstallationUpgradeModal
     */
    protected $modal;

    function setModal($modal)
    {
        $this->modal = $modal;
        return $this;
    }

    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->initIds('installationUpgradeForm');
        $this->setFormType('create');
        $this->setProvider(new UpgradeProvider);
        $this->loadRequestObj();
        $this->initFields();
        
    }

    protected function initFields()
    {
        $this->addField(new Fields\Hidden('installation_id'));
        $this->addField((new Fields\Hidden('version')));
        $this->setInternalAlertMessage('confirmInstallationUpgrade')
             ->addLocalLangReplacements(['version' => null]);
        //Backup
        $this->addField(new Fields\Switcher('backup'));
        try
        {
            if (!$this->request->get('ajax') || !$this->isReadDatatoForm())
            {
                return;
            }
            
            $this->dataProvider->upgradeRead();
            
        }
        catch (\Exception $ex)
        {
            $this->fields=[];
            $id = $this->formData['installation_id']? $this->formData['installation_id'] : $this->request->get('wpid');
            $this->setInstallationId($id)
                  ->setUserId($this->request->getSession('uid'));
            $this->setInternalAlertMessage('installationIsOnDate');
            $this->setInternalAlertMessageType(AlertTypesConstants::INFO)
                 ->addLocalLangReplacements(['version' => $this->getInstallation()->version]);
            $this->modal->initCloseButton();
        }
        $this->loadDataToForm();
    }
}
