<?php

/* * ********************************************************************
 * Servers\HetznerVps product developed. (Sep 21, 2018)
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

namespace ModulesGarden\Servers\HetznerVps\App\Repositories;

use ModulesGarden\Servers\HetznerVps as main;

/**
 * Description of RangeVmRepository
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class RangeVmRepository
{
    private $serverId;
    private $min;
    private $max;

    /**
     *
     * @var main\App\Models\RangeVm
     */
    private $model;

    public function __construct($serverId)
    {
        $this->serverId = $serverId;
        $this->model    = main\App\Models\RangeVm::where('server_id', $this->serverId)->first();
        if ($this->model)
        {
            $this->min = $this->model->vmid_from;
            $this->max = $this->model->vmid_to;
        }
        if (!$this->min)
        {
            $this->min = main\Core\Models\Whmcs\Configuration::where("setting", "hetznerVPSMinimumVMID")->value('value');
        }
    }

    public function has()
    {
        return $this->min > 0 || $this->max > 1;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }
}
