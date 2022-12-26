<?php

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

use Exception;
use ModulesGarden\WordpressManager\App\Interfaces\UserInterface;

/**
 * Description of Plugin
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class User implements UserInterface
{
    /**
     *
     * @var Wp
     */
    private $wp;

    private $roles = [
        'administrator' => 'administrator',
        'author'        => 'author',
        'contributor'   => 'contributor',
        'editor'        => 'editor',
        'subscriber'    => 'subscriber',
    ];

    function __construct(Wp $wp)
    {
        $this->wp = $wp;
    }

    public function get($id)
    {
        $request = [
            'command' => 'user',
            'action'  => 'get',
            'data'    => [$id, "--path={$this->wp->getPath()} --format=json"],
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function getList()
    {
        $request = [
            'command' => 'user',
            'action'  => 'list',
            'data'    => ["--path={$this->wp->getPath()} --fields=user_login,display_name,user_email,user_registered,roles,ID --format=json"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);

        return $this->wp->getApi()->sendWpRequest();
    }

    public function create($user)
    {
        $params = $this->toString($user);

        $request = [
            'command' => 'user',
            'action'  => 'create',
            'data'    => ["--path={$this->wp->getPath()} $params"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);

        return $this->wp->getApi()->sendWpRequest();
    }

    public function delete($id)
    {
        $request = [
            'command' => 'user',
            'action'  => 'delete',
            'data'    => [$id, "--path={$this->wp->getPath()} --yes"],
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function update($user)
    {
        $params = $this->toString($user);

        $request = [
            'command' => 'user',
            'action'  => 'update',
            'data'    => ["--path={$this->wp->getPath()} $params --user_email={$user['email']}"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    public function resetPassword($id)
    {

        $request = [
            'command' => 'user',
            'action'  => 'reset-password',
            'data'    => ["--path={$this->wp->getPath()} $id"]
        ];

        $this->wp->getApi()->setGet([]);
        $this->wp->getApi()->setPost($request);
        return $this->wp->getApi()->sendWpRequest();
    }

    private function toString(array $formData)
    {
        $array = $this->setParams($formData);

        $id = $formData['id'] . ' ' . strtolower($formData['login']) . ' ' . $formData['email'];

        $str = "$id ";
        foreach ($array as $key => $value) {
            $str .= $key . '=' . $value . '';
        }

        if ($formData['notify'] == 'on') {
            $str .= ' --send-email';
        }

        return $str;
    }

    private function setParams($formData)
    {
        if (strpos($formData['displayName'], ' ') !== false) {
            throw new Exception('Too many positional arguments on "Display Name"');
        }

        $params = [
            ' --user_pass'    => $formData['password'],
            ' --display_name' => $formData['displayName'] ?? strtolower($formData['login']),
            ' --role'         => $this->roles[$formData['roles']],
            ' --nickname'     => $formData['nickname'],
        ];

        foreach ($params as $key => $value) {
            if (!$value) {
                unset($params[$key]);
            }
        }

        return $params;
    }
}
