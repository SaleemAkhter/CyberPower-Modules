<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\WordPressWidgets\Providers;

use ModulesGarden\WordpressManager\App\UI\Installations\Providers\InstallationProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;
use \ModulesGarden\WordpressManager\Core\Helper;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Helper\Wp;



class WordPressManagerProvider extends InstallationProvider
{
    public function deleteAndRedirect()
    {


        /* @var $request \ModulesGarden\WordpressManager\Core\Http\Request */
        $request      = Helper\sl('request');

        /* @var  $installation  Installation */
        $installation = Installation::where('id', $request->get('wpid'))
            ->where('user_id', $request->getSession('uid'))
            ->firstOrFail();
        $module       = Wp::subModule($installation->hosting);
        if ($installation->username)
        {
            $module->setUsername($installation->username);
        }
        $form = $request->get('formData');
        $module->installationDelete($installation->relation_id, (array) $form);
        $installation->delete();
        Helper\infoLog(sprintf('Installation ID #%s has been deleted successfully, Client ID #%s, Hosting ID #%s', $installation->id, $request->getSession('uid'), $installation->hosting_id));


        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('Installation has been deleted successfully')
            ->setData($request->get('id'))
            ->setCallBackFunction('wpIntegrationInstallationDeleteAjaxDone');
    }
}