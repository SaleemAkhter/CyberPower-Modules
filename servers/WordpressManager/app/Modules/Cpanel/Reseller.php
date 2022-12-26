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

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

/**
 * Description of User
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Reseller
{
    /**
     *
     * @var Uapi
     */
    private $api;
    
    public function __construct(Uapi $api)
    {
        $this->api = $api;
    }
    
    public function getAccounts(){
        $response = $this->api->exec('list_accounts', '/execute/Resellers/');
        $data=[];
        foreach( $response['data'] as $result){
           $data[]= [
               'username'   => $result['user']
           ];
        }
        return $data;
    }
}
