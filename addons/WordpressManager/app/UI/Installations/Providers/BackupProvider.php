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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;
use ModulesGarden\WordpressManager\App\Helper\Wp;
use ModulesGarden\WordpressManager\App\Repositories\InstallationRepository;
Use ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;

/**
 * Description of InstallationProvider
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class BackupProvider extends BaseDataProvider implements ClientArea
{

    public function create()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $form         = $request->get('formData');
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if( $installation->username){
            $module->setUsername($installation->username);
        }
        $data         = [
            'installationId'  => $installation->relation_id,
            'backupDirectory' => $form['backupDirectory'] == 'on' ? 1 : 0,
            'backupDataDir'   => $form['backupDataDir'] == 'on' ? 1 : 0,
            'backupDatabase'  => $form['backupDatabase'] == 'on' ? 1 : 0,
            'backup_note'            => $form['backup_note']
        ];
        $module->backupCreate($data);
        Helper\infoLog(sprintf("Backup creating in progress, Installation ID #%s, Client ID #%s", $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Backup creating in progress');
    }

    public function read()
    {
        $this->data['id'] = $this->actionElementId;
    }

    public function update()
    {
        
    }

    public function delete()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if( $installation->username){
            $module->setUsername($installation->username);
        }
        $module->backupDelete($this->formData['id'], $installation);
        Helper\infoLog(sprintf("Backup '%s' has been deleted successfully, Installation ID #%s, Client ID #%s", $this->formData['id'], $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Backup has been deleted successfully');
    }

    public function restore()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if( $installation->username){
            $module->setUsername($installation->username);
        }
        $module->backupRestore($this->formData['id'],  $installation );
        Helper\infoLog(sprintf("Backup '%s' has been restored successfully, Installation ID #%s, Client ID #%s", $this->formData['id'], $installation->id, $request->getSession('uid')));
        return (new ResponseTemplates\HtmlDataJsonResponse())
               ->setMessageAndTranslate('Backup has been restored successfully');
    }

    public function deleteMass()
    {
        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');
        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
                ->where('user_id', $request->getSession('uid'))
                ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if( $installation->username){
            $module->setUsername($installation->username);
        }
        foreach ($this->getRequestValue('massActions') as $backupId)
        {
            $module->backupDelete($backupId,  $installation );
        }
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('Backups have been deleted successfully');
    }
}
