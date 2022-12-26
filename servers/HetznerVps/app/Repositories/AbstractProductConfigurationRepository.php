<?php

/* * ********************************************************************
 * HetznerVPS product developed. (2019-09-06)
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

use ModulesGarden\Servers\HetznerVps\Core\Helper\WhmcsVersionComparator;
use ModulesGarden\Servers\HetznerVps\App\Models\ProductConfiguration;

/**
 * Description of ProductConfigurationRepository
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 * @version 1.0.1
 */
abstract class AbstractProductConfigurationRepository
{
    protected $productId;
    /**
     *
     * @var ProductConfiguration[]
     */
    protected $entries;
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
        return empty($this->entries);
    }

    public function flush()
    {
        ProductConfiguration::ofProductId($this->productId)
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
            ProductConfiguration::ofProductId($this->productId)
                ->whereIn('setting', $keys)
                ->delete();
            foreach ($keys as $k)
            {
                unset($this->entries[$k]);
            }
        }
        else
        {
            ProductConfiguration::where('product_id', $this->productId)
                ->where('setting', $keys)
                ->limit(1)
                ->delete();
            unset($this->entries[$keys]);
        }
        return $this;
    }

    public function all($default = [])
    {
        $this->force = true;
        $decode = !WhmcsVersionComparator::isWhmcsVersionHigherOrEqual('8.0.0');
        foreach (ProductConfiguration::ofProductId($this->productId)->pluck('value', 'setting')->all() as $key => $value)
        {
            if($decode){
                $value                = json_decode($value, true);
            }
            $this->entries[$key] = $value;
        }
        if (!$this->isEmpty())
        {
            return $this->entries;
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
        return ProductConfiguration::ofProductId($this->productId)
                ->where('setting', $key)
                ->count() > 0;
    }

    public function set($key, $value)
    {
        $this->enterie[$key] = $value;
    }

    public function __isset($key)
    {
        return isset($this->entries[$key]);
    }

    public function get($key, $default = null)
    {
        if (isset($this->entries[$key]))
        {
            return $this->entries[$key];
        }
        else
        {
            if (!$this->force)
            {
                $this->getEntery($key);
                if (isset($this->entries[$key]))
                {
                    return $this->entries[$key];
                }
            }
        }
        return $default;
    }

    /**
     *
     * @param string $key
     * @return ProductConfiguration
     */
    protected function getEntery($key)
    {
        if (isset($this->entries[$key]))
        {
            return $this->entries[$key];
        }
        else
        {
            if (!$this->force)
            {
                $this->entries[$key] = ProductConfiguration::ofProductId($this->productId)
                    ->ofSetting($key)
                    ->value("value");
                return $this->entries[$key];
            }
        }
    }

    public function fill(array $setting)
    {
        $this->entries = $setting;
        return $this;
    }

    public function fillAndSave(array $setting)
    {
        $this->entries = $setting;
        foreach ($this->entries as $key => $value)
        {
            $setting =  ProductConfiguration::firstOrNew([
                'product_id' => $this->productId,
                'setting' => $key
            ]);
            $setting->value      = $value;
            $setting->save();
        }
        return $this;
    }

    public function save()
    {
        foreach ($this->entries as $key => $value)
        {
            $setting             = new ProductConfiguration();
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

}