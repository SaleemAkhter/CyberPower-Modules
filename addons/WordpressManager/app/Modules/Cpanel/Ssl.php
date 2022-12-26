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

namespace ModulesGarden\WordpressManager\App\Modules\Cpanel;

use \ModulesGarden\WordpressManager as main;
/**
 * Description of Sll
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Ssl implements main\App\Interfaces\SslInterface
{

    private $api;
    /**
     * @var main\App\Models\Installation
     */
    private $installation;
    
    public function __construct( $api)
    {
        $this->api = $api;
    }

    public function on(){
        
    }
    
    public function off(){
        
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
