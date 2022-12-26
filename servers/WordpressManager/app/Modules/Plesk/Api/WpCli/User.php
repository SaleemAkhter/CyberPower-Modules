<?php

namespace ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

use ModulesGarden\WordpressManager\App\Interfaces\UserInterface;
use ModulesGarden\WordpressManager\App\Modules\Plesk\Api\WpCli;

class User implements UserInterface
{

    private $roles = [
        'administrator' => 'administrator',
        'author'        => 'author',
        'contributor'   => 'contributor',
        'editor'        => 'editor',
        'subscriber'    => 'subscriber',
    ];

    /**
     * @var WpCli
     */
    private $wpcli;
    private $defaultParams;

    /**
     * Option constructor.
     * @param WpCli $wpcli
     */
    public function __construct(WpCli $wpcli)
    {
        $this->wpcli = $wpcli;
        $this->defaultParams = ' --skip-plugins --skip-themes';
    }

    public function get($id)
    {
        $request  =[
            "user", "get", $id, "--path={$this->wpcli->getPath()} $this->defaultParams --format=json",
        ];

        return $this->wpcli->call($request);
    }

    public function getList()
    {
        $request = [
            "user",
            "list",
            "--path={$this->wpcli->getPath()} $this->defaultParams --fields=user_login,display_name,user_email,user_registered,roles,ID --format=json"
        ];

        return $this->wpcli->call($request);
    }

    public function create($user)
    {
        $params = $this->toString($user);

        $request = [
            "user",
            "create",
            $params,
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);
    }

    public function delete($id)
    {
        $request = [
            "user",
            "delete",
            $id,
            "--path={$this->wpcli->getPath()} $this->defaultParams --yes"
        ];

        return $this->wpcli->call($request);
    }

    public function update($user)
    {
        $params = $this->toString($user);

        $request = [
            "user",
            "update",
            $params,
            "--user_email={$user['email']}",
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);
    }

    public function resetPassword($id)
    {
        $request = [
            "user",
            "reset-password",
            $id,
            "--path={$this->wpcli->getPath()} $this->defaultParams"
        ];

        return $this->wpcli->call($request);
    }

    private function toString(array $formData)
    {
        $array = $this->setParams($formData);

        $id = $formData['id'] . ' ' . strtolower($formData['login']) . ' ' . $formData['email'];

        $str = "$id ";
        foreach ($array as $key => $value)
        {
            $str .= $key . '=' . $value . '';
        }

        if ($formData['notify'] == 'on')
        {
            $str .= ' --send-email';
        }

        return $str;
    }

    private function setParams($formData)
    {
        $params = [
            ' --user_pass'    => $formData['password'],
            ' --display_name' => $formData['displayName'],
            ' --role'         => $this->roles[$formData['roles']],
            ' --user_url'     => $formData['website'],
            ' --nickname'     => $formData['nickname'],
            ' --first_name'   => $formData['firstname'],
            ' --last_name'    => $formData['lastname'],
            ' --description'  => $formData['description'],
        ];

        foreach ($params as $key => $value)
        {
            if (!$value)
            {
                unset($params[$key]);
            }
        }

        return $params;
    }
}
