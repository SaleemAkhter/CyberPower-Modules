<?php

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

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
            'params'  => "--path={$this->wp->getPath()} --format=json",
            'value'   => $id,
        ];

        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function getList()
    {
        $request = [
            'command' => 'user',
            'action'  => 'list',
            'params'  => "--path={$this->wp->getPath()} --fields=user_login,display_name,user_email,user_registered,roles,ID --format=json"
        ];

        $data = $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);

        return $data['data'];
    }

    public function create($user)
    {
        $params = $this->toString($user);

        $request = [
            'command' => 'user',
            'action'  => 'create',
            'params'  => "--path={$this->wp->getPath()} $params",
        ];

        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function delete($id)
    {
        $request = [
            'command' => 'user',
            'action'  => 'delete',
            'params'  => "--path={$this->wp->getPath()} --yes",
            'value'   => $id,
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function update($user)
    {
        $params = $this->toString($user);

        $request = [
            'command' => 'user',
            'action'  => 'update',
            'params'  => "--path={$this->wp->getPath()} $params --user_email={$user['email']}"
        ];

        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    public function resetPassword($id)
    {
        
        $request = [
            'command' => 'user',
            'action'  => 'reset-password',
            'params'  => "--path={$this->wp->getPath()} $id"
        ];
        return $this->wp->getUapi()->exec('api', Wp::END_POINT, $request);
    }

    private function toString(array $formData)
    {
        $array = $this->setParams($formData);

        $id =  $formData['id'] . ' ' . strtolower($formData['login']) . ' ' . $formData['email'];

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
        $params = [
            ' --user_pass'      => $formData['password'],
            ' --display_name'   => $formData['displayName'],
            ' --role'           => $this->roles[$formData['roles']],
            ' --nickname'       => $formData['nickname'],
        ];

        foreach ($params as $key => $value) {
            if (!$value) {
                unset($params[$key]);
            }
        }

        return $params;
    }
}
