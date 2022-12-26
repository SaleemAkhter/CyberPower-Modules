<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Data;

/**
 * Description of Addon
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Addon
{
    /**
     * @var \ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Data
     */
    protected $data;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $configFields = [
        'name',
        'description',
        'version',
        'author',
        'fields',
        'systemName',
        'debug'
    ];

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function config()
    {
        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Config\Before::class, 'execute');

            foreach ($this->configFields as $field)
            {
                $value = $this->data->{$field};
                if (isset($return[$field]) === false && $value !== null)
                {
                    if (is_numeric($value))
                    {
                        $return[$field] = (int)$value;
                    }
                    else
                    {
                        $return[$field] = $value;
                    }
                }
            }

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Config\After::class)->execute($return);

            $this->config = $return;

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);

            return $return|[];
        }
    }

    public function activate()
    {
        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Activate\After::class, 'execute');

            if (!isset($return['status']))
            {
                $return['status'] = 'success';
            }

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Activate\Before::class)->execute($return);

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);
            return [
                'status' => 'error',
                'description' => $ex->getMessage()
            ];
        }
    }

    public function deactivate()
    {
        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Deactivate\After::class, 'execute');



            if (!isset($return['status']))
            {
                $return['status'] = 'success';
            }

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Deactivate\Before::class)->execute($return);

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);
            return [
                'status' => 'error',
                'description' => $ex->getMessage()
            ];
        }
    }

    public function update($version)
    {
        try
        {
            // after
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Update\After::class)->execute(['version' => $version]);

            // update
            if (!isset($return['version']))
            {
                $return['version'] = $version;
            }
            $patchManager = ServiceLocator::call("patchManager")->run($this->getConfig("version"), $version);

            // before
            $return = ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\Configuration\Addon\Update\Before::class)->execute($return);

            return $return;
        }
        catch (\Exception $ex)
        {
            ServiceLocator::call(\ModulesGarden\Servers\DigitalOceanDroplets\Core\HandlerError\ErrorManager::class)->addError(self::class, $ex->getMessage(), $return);
        }
    }

    public function getConfig($key = null, $default = null)
    {

        if (empty($key))
        {
            return $this->data->getAll();
        }
        $return = $this->data->{$key};

        return ($return !== null) ? $return : $default;
    }
}
