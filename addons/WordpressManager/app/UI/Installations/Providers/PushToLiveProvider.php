<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 26, 2018)
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

use function\ModulesGarden\WordpressManager\Core\Helper\sl;
use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

/**
 * Description of PushToLiveProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PushToLiveProvider extends BaseDataProvider implements ClientArea
{

    use BaseClientController;

    public function read()
    {
        if (!$this->getRequestValue('actionElementId') && !$this->getRequestValue('wpid'))
        {
            return;
        }
        $this->data = $this->formData;
        $wpid       = $this->getRequestValue('actionElementId') ? $this->getRequestValue('actionElementId') : $this->getRequestValue('wpid');
        $this->setInstallationId($wpid)
            ->setUserId($this->request->getSession('uid'));
        $this->data['installation_id'] = $wpid;
        $this->data['url']             = $this->getInstallation()->staging()->url;


        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $data            = $this->subModule()->installation($this->getInstallation())->pushToLive([]);
        $tablesStructure = [];
        foreach ($data['tables_structure_diff'] as $table)
        {
            $tablesStructure[$table] = $table;
        }
        $tablesData = [];
        foreach ($data['tables_data_diff'] as $table)
        {
            $tablesData[$table] = $table;
        }
        //options
        $this->availableValues['structural_change_tables'] = $tablesStructure;
        $this->availableValues['datachange_tables']        = $tablesData;
        //selected
        $this->data['structural_change_tables'] = $tablesStructure;
        $this->data['datachange_tables']        = $tablesData;
    }

    public function create()
    {
        $this->reset()
            ->setInstallationId($this->formData['installation_id'])
            ->setUserId($this->request->getSession('uid'));
        $post = [];
        if ($this->formData['custom_push'] == "on")
        {
            $post = [
                "custom_push" => 1,
            ];
            if ($this->formData['overwrite_files'] == "on")
            {
                $post['overwrite_files'] = 1;
            }
            if ($this->formData['push_db'] == "on")
            {
                $post['push_db'] = 1;
            }
            if ($this->formData['push_views'] == "on")
            {
                $post['push_views'] = 1;
            }
            if ($this->formData['structural_change_tables'])
            {
                $post['structural_change_tables'] = $this->formData['structural_change_tables'];
            }
            if ($this->formData['datachange_tables'])
            {
                $post['datachange_tables'] = $this->formData['datachange_tables'];
            }
        }
        $post['softsubmit'] = 1;
        if ($this->getInstallation()->username)
        {
            $this->subModule()->setUsername($this->getInstallation()->username);
        }
        $this->subModule()->installation($this->getInstallation())->pushToLive($post);
        sl('lang')->addReplacementConstant('url', $this->formData['url']);
        Helper\infoLog(sprintf("The Staging installation has been pushed to live at: %s, Installation ID %s, Client ID #%s", $this->formData['url'], $this->getInstallation()->id, $this->getInstallation()->user_id));
        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('The staging installation has been pushed successfully to live installation: :url:');
    }

    public function update()
    {

    }
}
