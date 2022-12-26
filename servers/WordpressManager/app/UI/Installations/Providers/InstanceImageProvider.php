<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 24, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI\Installations\Providers;

use \ModulesGarden\WordpressManager\App\Models\InstanceImage;
use \ModulesGarden\WordpressManager\Core\Helper;

/**
 * Description of InstanceImageProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class InstanceImageProvider extends ImportProvider
{

    public function read()
    {
        $this->data = $this->formData;
        //new db name
        $randGen = new Helper\RandomStringGenerator(7, true, true, true);
        $this->data['softdb'] = $randGen->genRandomString();        
    }

    public function create()
    {
        /* @var $instanceImage InstanceImage */
        $instanceImage = InstanceImage::findOrFail($this->formData['instanceImageId']);
        unset($this->formData['instanceImageId']);
        $fills         = ['protocol', 'server_host', 'port', 'ftp_user', 'ftp_path', 'installed_path', 'soft', 'domain'];
        foreach ($fills as $attr)
        {
            $this->formData[$attr] = $instanceImage->{$attr};
        }
        $this->formData['ftp_pass'] = decrypt($instanceImage->ftp_pass);
        if($instanceImage->installed_path)
        {
            $this->formData['Installed_path'] = $instanceImage->installed_path;
            unset($this->formData['installed_path']);
        }
        
        return parent::create();
    }
}
