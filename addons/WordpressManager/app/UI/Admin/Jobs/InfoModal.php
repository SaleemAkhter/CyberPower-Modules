<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 19, 2017)
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

use \ModulesGarden\WordpressManager\Core\UI\Widget\Modals\BaseEditModal;
use \ModulesGarden\WordpressManager\Core\UI\Interfaces\AdminArea;
use \ModulesGarden\WordpressManager\Core\UI\Widget\Buttons\ModalActionButtons\BaseCancelButton;
use \ModulesGarden\WordpressManager as main;
use \ModulesGarden\WordpressManager\Core\Queue\Models\Job;
use \ModulesGarden\WordpressManager\Core\Queue\Models\JobLog;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;

/**
 * Description of CloneModal
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class InfoModal extends BaseEditModal implements AdminArea
{
    /**
     *
     * @var Job
     */
    protected $job;

    public function initContent()
    {
        $this->initIds('infoModal');
        if (!$this->getRequestValue('actionElementId'))
        {
            return;
        }
        //Job
        $this->job                     = Job::findOrFail($this->getRequestValue('actionElementId'));
        $this->customTplVars['job']    = $this->job->toArray();
        //Job status
        $status                        = $this->job->status ? $this->job->status : 'pending';
        $this->customTplVars['status'] = (new StatusLabel())->setStatus($status)->getHtml();
        $this->customTplVars['data']                         = unserialize($this->job->data);
        if ($this->customTplVars['data']['hostingId'])
        {
            /*@var $hosting Hosting*/
            $hosting = Hosting::findOrFail($this->customTplVars['data']['hostingId']);
            $this->customTplVars['productName'] = $hosting->product->name;
            
        }
        elseif ($this->customTplVars['data']['installationId']){
            /*@var $installation main\App\Models\Installation*/
            if(empty(main\App\Models\Installation::find($this->customTplVars['data']['installationId'])))
                return;
            $installation = main\App\Models\Installation::findOrFail($this->customTplVars['data']['installationId']);
            $this->customTplVars['productName'] = $installation->hosting->product->name;
            $this->customTplVars['data']['hostingId'] =  $installation->hosting_id;
        }
        //JobLogs
        foreach (JobLog::where('job_id', $this->job->id)->orderBy('id', 'desc')->get() as $jobLog)
        {
            $log                              = $jobLog->toArray();
            $log['status']                    = (new StatusLabel())->setStatus($jobLog->type)->getHtml();
            $log['created_at'] = main\App\Helper\WhmcsHelper::fromMySQLDate($jobLog->created_at,true);
            $this->customTplVars['jobLogs'][] = $log;
        }
    }

    protected function initActionButtons()
    {
        if (!empty($this->actionButtons))
        {
            return $this;
        } 
        $this->addActionButton(new BaseCancelButton);
        return $this;
    }
}
