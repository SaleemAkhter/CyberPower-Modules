<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 24, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;


/**
 * Description of InstanceImageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class MaintenanceModeProvider extends ImportProvider
{
    use BaseClientController;
    private $maintenanceMode;
    public $status;

    public function read()
    {
        $this->loadRequestObj();
        $wpid = $this->request->get('wpid') ?? $this->formData['id'];
        $this->setInstallation(Installation::find($wpid));
        $this->maintenanceMode = ($this->subModule()->getWpCli($this->getInstallation()))->maintenance();
        $this->data['status'] = $this->maintenanceMode->checkStatus();
        $this->status = $this->data['status'];
        $this->data['id']     = $this->request->get('wpid');

    }

    public function create()
    {
        $this->read();

        if (str_contains($this->data['status'], 'not')) {
            $this->maintenanceMode->enable();
        } else {
            $this->maintenanceMode->disable();
        }

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('maintenanceModeChanged');
    }
}
