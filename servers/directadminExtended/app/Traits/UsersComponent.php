<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Traits;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

trait UsersComponent
{
    protected $userList = [];


    protected function buildUserList()
    {

        if($this->getWhmcsParamByKey('producttype')  == "server" ){
            if(!$this->resellerApi && method_exists($this,'loadAdminApi'))
            {
                $this->loadAdminApi();
            }

            if($this->adminApi)
            {
                $result = $this->adminApi->reseller->listAllUsers();

                foreach ($result as $user)
                {
                    if(is_array($user)){
                        $this->userList[$user['username']] = $user['username'];
                    }elseif(is_object($user)){
                        $this->userList[$user->username->value] = $user->username->value;
                    }else{
                        $this->userList[$user->getName()] = $user->getName();
                    }

                }
            }
        }else{

            if(!$this->resellerApi && method_exists($this,'loadResellerApi'))
            {
                $this->loadResellerApi();
            }

            if($this->resellerApi)
            {
                $result = $this->resellerApi->reseller->listUsers();

                foreach ($result as $user)
                {
                    if(is_array($user)){
                        $this->userList[$user['username']] = $user['username'];
                    }else{
                        $this->userList[$user->getName()] = $user->getName();
                    }

                }
            }
        }
    }

    protected function getUserList()
    {
        if(!$this->userList)
        {
            $this->buildUserList();
        }

        return $this->userList;
    }
    protected function checkUserExists($user)
    {
        if(!$this->userList)
        {
            $this->buildUserList();
        }

        return isset($this->userList[$user]);
    }


}
