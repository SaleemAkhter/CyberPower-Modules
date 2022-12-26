<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 18, 2018)
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

use \ModulesGarden\WordpressManager as main;
/**
 * Description of Sll
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Ssl implements main\App\Interfaces\SslInterface
{
    /**
     * @var DirectAdminApi
     */
    private $api;
    /**
     * @var main\App\Models\Installation
     */
    private $installation;
    
    public function __construct(DirectAdminApi $api)
    {
        $this->api = $api;
    }

    public function on(){
        //Get current domain
        $domain = $this->api->getAddonDomain($this->installation->domain);

        //Enable SSL in domain if not enabled already
        if(!$domain->getSsl())
        {
            $domain->setSsl(true);
            $this->api->updateAddonDomain($domain);
        }

        //Check if certificate is already set
        if($domain->getSslCertificateFile())
        {
            return true;
        }
        $company = $this->installation->client->companyname ? $this->installation->client->companyname : 
                $this->installation->client->firstname.' '.$this->installation->client->lastname;
        $post=[
            'action'    =>  'save',
            'type'    =>  'create',
            'request'    =>  'letsencrypt',
            'domain'    => $this->installation->domain,
            'company'   => $company,
            'email'     => $this->installation->client->email,
            'keysize'   => 2048,
            'name'      =>  $this->installation->domain,
            'encryption'  => 'sha256',
            'le_select0' => $this->installation->domain,  
            'le_select1' => 'www.'.$this->installation->domain
        ];
        $this->api->setGet([]);
        $this->api->setPost($post);
        $this->api->setCommand('CMD_API_SSL');
        return $this->api->sendRequest();
    }
    
    public function off(){
        //Do nothing
    }
    
    public function getInstallation()
    {
        return $this->installation;
    }

    public function setInstallation(main\App\Models\Installation $installation)
    {
        $this->installation = $installation;
        return $this;
    }
}
