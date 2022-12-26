<?php

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\Core\UI\Interfaces\ClientArea;
use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\Core\UI\ResponseTemplates;

/**
 * Description of UserProvider
 *
 * @author Daniel Heinze <daniel.he@modulesgarden.com>
 */
class UserProvider extends BaseDataProvider implements ClientArea
{
    use BaseClientController;

    public function read()
    {
        $this->loadRequestObj();

        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $this->user = ($this->subModule()->getWpCli($this->getInstallation()))->user();

        $user = $this->user->get($this->actionElementId);

        $this->data['login']       = $user['user_login'];
        $this->data['email']       = $user['user_email'];
        $this->data['displayName'] = $user['display_name'];
        $this->data['roles']       = $user['roles'];
        $this->data['nickname']    = $user['nickname'] ?? $user['user_login'];
        $this->data['id']          = $this->actionElementId;
        $this->data['website']     = $user['user_url'];

        $this->data['firstname']   = $user['firstname'];
        $this->data['lastname']    = $user['lastname'];
        $this->data['description'] = $user['description'];


        $this->availableValues['cron']  = [
            'administrator' => 'administrator',
            'author'        => 'author',
            'contributor'   => 'contributor',
            'editor'        => 'editor',
            'subscriber'    => 'subscriber',
        ];
    }

    public function create()
    {
        $this->loadRequestObj();
        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $user = ($this->subModule()->getWpCli($this->getInstallation()))->user();
        $user->create($this->formData);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('userCreated');
    }

    public function update()
    {
        $this->loadRequestObj();
        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $user = ($this->subModule()->getWpCli($this->getInstallation()))->user();
        $user->update($this->formData);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('userUpdated');
    }

    public function delete()
    {
        $this->loadRequestObj();
        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $user = ($this->subModule()->getWpCli($this->getInstallation()))->user();
        $user->delete($this->formData['id']);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('userDeleted');
    }

    public function put()
    {
        $this->loadRequestObj();
        $this->setInstallation(Installation::find($this->request->get('wpid')));
        $user = ($this->subModule()->getWpCli($this->getInstallation()))->user();
        $user->resetPassword($this->formData['id']);

        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('userPasswordReset');
    }
}
