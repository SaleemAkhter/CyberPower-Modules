<?php

/* * ********************************************************************
 * ProxmoxVPS product developed. (2019-09-06)
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

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Repositories;

use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\ProductSettings\Model;

/**
 * Description of ModelRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.1
 */
abstract class AbstractProductSettingRepository
{
    protected $productId;
    /**
     *
     * @var Model[]
     */
    protected $enteries;
    protected $force = false;

    public function __construct($productId)
    {
        if (!is_numeric($productId) || $productId <= 0)
        {
            throw new \InvalidArgumentException(sprintf("Product id: %s is invalid", $productId));
        }
        $this->productId = $productId;
    }

    /**
     * @return int|string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int|string $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function getForce()
    {
        return $this->force;
    }

    public function setForce($force)
    {
        $this->force = $force;
        return $this;
    }

    public function isEmpty()
    {
        return empty($this->enteries);
    }

    public function flush()
    {
        Model::ofProductId($this->productId)
            ->whereNotIn('setting', ['version'])
            ->delete();
        return $this;
    }

    /**
     * Delete keys
     * @param string $keys
     * @param array $keys
     * @return $this
     */
    public function forget($keys)
    {
        if (is_array($keys))
        {
            Model::ofProductId($this->productId)
                ->whereIn('setting', $keys)
                ->delete();
            foreach ($keys as $k)
            {
                unset($this->enteries[$k]);
            }
        }
        else
        {
            Model::where('product_id', $this->productId)
                ->where('setting', $keys)
                ->limit(1)
                ->delete();
            unset($this->enteries[$keys]);
        }
        return $this;
    }

    public function all($default = [])
    {
        $this->force = true;
        foreach (Model::ofProductId($this->productId)->pluck('value', 'setting')->all() as $key => $value)
        {
            $jsonValue = json_decode($value, true);

            /* Can be null if variable is not proper json */
            $this->enteries[$key] = $jsonValue ?: $value;
        }
        if (!$this->isEmpty())
        {
            return $this->enteries;
        }
        return $default;
    }

    public function store(array $values)
    {
        foreach ($values as $k => $v)
        {
            $this->set($k, $v);
        }
        return $this;
    }

    public function exist($key)
    {
        return Model::ofProductId($this->productId)
                   ->where('setting', $key)
                   ->count() > 0;
    }

    public function set($key, $value)
    {
        $this->enterie[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->enteries[$key]);
    }

    public function get($key, $default = null)
    {
        if (isset($this->enteries[$key]))
        {
            return $this->enteries[$key];
        }
        else
        {
            if (!$this->force)
            {
                $this->getEntery($key);
                if (isset($this->enteries[$key]))
                {
                    return $this->enteries[$key];
                }
            }
        }
        return $default;
    }

    /**
     *
     * @param string $key
     * @return Model
     */
    protected function getEntery($key)
    {
        if (isset($this->enteries[$key]))
        {
            return $this->enteries[$key];
        }
        else
        {
            if (!$this->force)
            {
                $this->enteries[$key] = Model::ofProductId($this->productId)
                    ->ofSetting($key)
                    ->value("value");
                return $this->enteries[$key];
            }
        }
    }

    public function fill(array $setting)
    {
        $this->enteries = $setting;
        return $this;
    }

    public function save()
    {
        foreach ($this->enteries as $key => $value)
        {
            $setting             = new Model();
            $setting->product_id = $this->productId;
            $setting->setting    = $key;
            $setting->value      = $value;
            $setting->save();
        }
    }

    public function getVersion()
    {
        return $this->get('version');
    }

    public function setVersion($version)
    {
        $this->set('version', $version);
        return $this;
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
