<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Jul 17, 2018)
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

namespace ModulesGarden\WordpressManager\App\UI;

use ModulesGarden\WordpressManager\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;
use \ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\App\Interfaces\WordPressModuleInterface;
use \ModulesGarden\WordpressManager\App\Helper\Wp;
/**
 * Description of BaseProvider
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
abstract class BaseProvider extends BaseDataProvider
{
    
    /**
     *
     * @var Installation
     */
    private $installation;
    
    /**
     *
     * @var  WordPressModuleInterface
     */
    private $module;
    
    public function getModule()
    {
        if(is_null($this->module)){
            $this->module = Wp::subModule($this->getInstallation()->hosting);
        }
        return $this->module;
    }

    public function getInstallation()
    {
        return $this->installation;
    }

    public function setModule(WordPressModuleInterface $module)
    {
        $this->module = $module;
        return $this;
    }

    public function setInstallation(Installation $installation)
    {
        $this->installation = $installation;
        return $this;
    }


    
}
