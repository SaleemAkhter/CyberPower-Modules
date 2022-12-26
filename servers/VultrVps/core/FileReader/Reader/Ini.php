<?php

namespace ModulesGarden\Servers\VultrVps\Core\FileReader\Reader;

use ModulesGarden\Servers\VultrVps\Core\ServiceLocator;
use Piwik\Ini\IniReader;
use Piwik\Ini\IniReadingException;

/**
 * Description of Ini
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Ini extends AbstractType
{

    protected function loadFile()
    {
        $return = [];
        $reader = new IniReader();
        try
        {
            if (file_exists($this->path . DS . $this->file))
            {
                $return = $reader->readFile($this->path . DS . $this->file);
            }
        }
        catch (IniReadingException $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }

        $this->data = $return;
    }
}
