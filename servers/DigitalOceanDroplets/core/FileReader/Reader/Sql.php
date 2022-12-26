<?php
namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ModuleConstants;

/**
 * Description of Sql
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class Sql extends AbstractType
{

    protected function loadFile()
    {
        $return = '';
        try
        {
            if (file_exists($this->path . DS . $this->file))
            {
                $collation = $this->getWHMCSTablesCollation();
                $return = file_get_contents($this->path . DS . $this->file);
                $return = str_replace("#collation#", $collation, $return);
                $return = str_replace("#prefix#", ModuleConstants::getPrefixDataBase(), $return);
                
            }
        }
        catch (\Exception $e)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $e->getMessage(), $e->getTrace());
        }

        $this->data = $return;
    }
    
    protected function getWHMCSTablesCollation()
    {
        $pdo       = \Illuminate\Database\Capsule\Manager::connection()->getPdo();
        $statement = $pdo->prepare("SHOW TABLE STATUS WHERE name = 'tblconfiguration'"); // ToDo: problem z show table
        $statement->execute();
        $result    = $statement->fetchObject();

        return $result->Collation;
    }
}
