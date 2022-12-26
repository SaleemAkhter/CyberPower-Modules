<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 12, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Admin\Jobs;

use \ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseModelDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use \ModulesGarden\WordpressManager as main;
use  function\ModulesGarden\WordpressManager\Core\Helper\sl;


/**
 * Description of InstanceImageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class JobProvider extends BaseModelDataProvider implements AdminArea
{
    public function __construct()
    {
        parent::__construct(main\Core\Queue\Models\Job::class);
    }

    public function read()
    {
        parent::read();
        $job               = $this->data['job'];
        $this->data['job'] = sl('lang')->tr($job);
    }

    public function create()
    {
        
    }

    public function update()
    {
        
    }

    public function deleteMass()
    {
        foreach ($this->getRequestValue('massActions') as $id)
        {
            $this->model->where('id', $id)->delete();
        }
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('The selected jobs have been deleted successfully');
    }

    public function delete()
    {
        parent::delete();
        sl('lang')->addReplacementConstant('job', $this->formData['job']);
        return (new HtmlDataJsonResponse())->setMessageAndTranslate('Job :job: has been deleted successfully');
    }
}
