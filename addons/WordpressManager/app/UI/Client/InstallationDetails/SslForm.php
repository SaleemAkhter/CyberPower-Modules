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
use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Hidden;
Use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\Fields\Switcher;
/**
 * Description of SslForm
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class SslForm extends BaseForm implements ClientArea
{

    protected function getDefaultActions()
    {
        return ['ssl'];
    }

    public function initContent()
    {
        $this->initIds('sslForm');
        $this->setFormType('ssl');
        $this->setProvider( new SslProvider());
        $this->initFields();
        $this->loadDataToForm();
    }

    protected function initFields()
    {
        $this->setInternalAlertMessage('infoSsl', 'info', '');
        $this->addField(new Switcher('ssl'));
        $this->addField(new Switcher('backup'));
        $this->addField(new Hidden('wpid'));
    }
}
