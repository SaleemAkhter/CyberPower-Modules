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

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\PushToLiveProvider;
use ModulesGarden\WordpressManager\Core\UI\Helpers\AlertTypesConstants;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Select;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
use function ModulesGarden\WordpressManager\Core\Helper\sl;

/**
 * Description of InstallationPushToLiveForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PushToLiveForm extends BaseForm implements ClientArea
{
    use BaseClientController;
    use RequestObjectHandler;

    protected function getDefaultActions()
    {
        return ['create'];
    }

    public function initContent()
    {
        $this->initIds('pushToLiveForm');
        $this->setFormType('create');
        $this->setProvider(new PushToLiveProvider);
        $this->loadRequestObj();
        $this->initFields();
        $this->loadDataToForm();
    }

    private function initFields()
    {
        //Customize 
        $field = new Switcher('custom_push');
        $field->addHtmlAttribute('bi-event-change', "reloadVueModal");
        $this->addField($field);
        $isCustomize         = $this->request->get('formData')['custom_push'] == "on";
        $inputInstallationId = new Hidden('installation_id');
        $this->addField($inputInstallationId);
        //URL
        $url = new Hidden('url');
        $this->addField($url);
        $msg = "installationConfirmPushToLive";
        if ($isCustomize)
        {
            $msg = "installationConfirmPushToLiveCustomize";
        }
        $this->setInternalAlertMessage($msg, AlertTypesConstants::WARNING, AlertTypesConstants::DEFAULT_SIZE)
            ->addLocalLangReplacements(["url" => null]);
        //Customize fields
        if (!$isCustomize)
        {
            return;
        }
        //overwrite_files
        $this->addField((new Switcher('overwrite_files'))->setDefaultValue('on')->setDescription('description'));
        //push_views 
        $this->addField((new Switcher('push_views'))->setDescription('description'));
        //push_db
        $this->addField((new Switcher('push_db'))->setDescription('description'));
        if (!sl('request')->get('ajax') || !$this->isReadDatatoForm())
        {
            return;
        }
        $this->dataProvider->read();
        //structural_change_tables'
        $tables = new Select('structural_change_tables');
        if (!empty($this->dataProvider->getValueById($tables->getId())))
        {
            $tables->enableMultiple();
            $tables->setDescription('description');
            $this->addField($tables);
        }
        //datachange_tables 
        $datachange = new Select('datachange_tables');
        if (!empty($this->dataProvider->getValueById($datachange->getId())))
        {
            $datachange->enableMultiple();
            $datachange->setDescription('description');
            $this->addField($datachange);
        }

    }
}
