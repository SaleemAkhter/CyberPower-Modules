<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Forms;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use \ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\UI\Installations\Modals\MaintenanceModeModal;
use \ModulesGarden\WordpressManager\App\UI\Installations\Providers\MaintenanceModeProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\FormConstants;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields;


/**
 * Description of InstallationStagingForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MaintenanceModeForm extends BaseForm implements ClientArea
{
    use BaseClientController;

    protected $formData;

    /**
     * @var main\App\UI\Installations\Modals\MaintenanceModeModal
     */
    protected $modal;

    public function __construct(MaintenanceModeModal $modal)
    {
        $this->modal    = $modal;
        parent::__construct();
    }

    public function initContent()
    {
        $this->initIds('maintenanceModeForm');
        $this->setFormType(FormConstants::CREATE);
        $this->setProvider(new MaintenanceModeProvider);

        $this->addField(new Fields\Hidden('status'));
        $this->addField(new Fields\Hidden('id'));
  
        $this->loadRequestObj();
        $this->loadDataToForm();
        $this->setInternalAlertMessage($this->dataProvider->status);
       /*  ->addLocalLangReplacements(['status' => $this->formData['status']]); */
        
    }


}
