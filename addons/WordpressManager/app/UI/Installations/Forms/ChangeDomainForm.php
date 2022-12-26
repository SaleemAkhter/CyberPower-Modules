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

use ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\BaseForm;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;

/**
 * Description of CreateForm
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class ChangeDomainForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['changeDomain'];
    }

    public function initContent()
    {
        $this->initIds('changeDomainForm');
        $this->setFormType('changeDomain');
        $this->setProvider(new InstallationProvider());
        $this->initFields();
    }

    protected function initFields()
    {
        $wpId = $this->request->get('wpid') ?: $this->request->get('formData')['installation_id'];
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $wpId)
            ->where('user_id', $request->getSession('uid'))
            ->firstOrFail();
        $module       = main\App\Helper\Wp::subModule($installation->hosting);
        if ($module instanceof main\App\Modules\Cpanel || $module instanceof main\App\Modules\CpanelExtended)
        {
            $this->setInternalAlertMessage('infoChangeDomainCpanel', 'warning', '');
        }
        else
        {
            $this->setInternalAlertMessage('infoChangeDomain', 'warning', '');
        }

        foreach ($module->getChangeDomainFields($installation) as $field)
        {
            if ($field->getId() === 'newDomain')
            {
                $field->notEmpty();
            }

            $this->addField($field);
        }
        $this->addField(new Switcher('backup'));
        $this->addField((new Switcher('ssl'))->setDefaultValue($installation->isHttps() ? "on" : "off"));
        $inputInstallationId = new Hidden('installation_id');
        $inputInstallationId->setValue($wpId);
        $this->addField($inputInstallationId);
    }
}
