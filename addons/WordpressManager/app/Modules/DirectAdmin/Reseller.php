<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Dec 24, 2018)
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

namespace ModulesGarden\WordpressManager\App\Modules\DirectAdmin;

/**
 * Description of User
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Reseller
{
    /**
     *
     * @var  DirectAdminApi
     */
    private $api;
    
    public function __construct(DirectAdminApi $api)
    {
        $this->api = $api;
    }
    
    public function getAccounts(){
        
        $this->api->setPost([]);
        $this->api->setCommand('CMD_API_SHOW_USERS');
        $this->api->setGet([]);
        $data=[];
        $response = $this->api->sendRequest();
        foreach($response['list'] as $username){
            $data[]=['username' => $username];
        }
        return $data;
        
    }
}
