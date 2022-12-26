<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;

/**
 * Description of Json
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Json extends AbstractType
{

    protected function loadFile()
    {
        $return = [];
        try
        {
            if (file_exists($this->path . DS . $this->file))
            {
                $readFile = file_get_contents($this->path . DS . $this->file);
                $return   = json_decode($readFile, true);
            }
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }

        $this->data = $return;
    }
}
