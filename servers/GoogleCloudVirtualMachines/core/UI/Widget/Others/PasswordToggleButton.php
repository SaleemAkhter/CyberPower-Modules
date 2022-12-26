<?php

/* * ********************************************************************
 * GoogleCloudVirtualMachines product developed. (May 7, 2018)
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

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Widget\Others;

use \ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\UI\Builder\BaseContainer;
/**
 * Description of PasswordHiddenButton
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class PasswordToggleButton extends BaseContainer
{
    protected $iconOn           = 'lu-zmdi lu-zmdi-eye';
    protected $iconOff           = 'lu-zmdi lu-zmdi-eye-off';
    private $password;

    protected $vueComponent            = true;
    protected $defaultVueComponentName = 'mg-passtoogle';

    public function getIconOn()
    {
        return $this->iconOn;
    }

    public function getIconOff()
    {
        return $this->iconOff;
    }

    public function setIconOn($iconOn)
    {
        $this->iconOn = $iconOn;
        return $this;
    }

    public function setIconOff($iconOff)
    {
        $this->iconOff = $iconOff;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPasswordHidden(){
        return str_repeat('*', strlen($this->getPassword()));
    }
}
