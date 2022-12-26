<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\Core\Helper;

use ModulesGarden\Servers\DigitalOceanDroplets\Core\FileReader\Reader;
use ModulesGarden\Servers\DigitalOceanDroplets\Core\ServiceLocator;
use Illuminate\Database\Capsule\Manager;

/**
 * Autometes some of database queries
 *
 * @author
 */
class DatabaseHelper
{
    
    /**
     * Helper to perform raw queries for module
     *
     * @param string $file
     * @return array
     */
    public function performQueryFromFile($file = '')
    {
        return $this->checkIsAllSuccess(array_map([$this, "execute"], $this->getQueries($file)));
    }
    
    protected function checkIsAllSuccess(array $array = [])
    {
        return in_array(false, $array, true);
    }
    
    protected function execute(&$query)
    {
        try
        {
            $pdo = Manager::connection()->getPdo();
            if (empty($query) === false)
            {
                $statement = $pdo->prepare($query);
                $statement->execute();
            }
            $query = true;
        }
        catch (\PDOException $ex)
        {
            ServiceLocator::call('errorManager')->addError(self::class, $ex->getMessage(), ['query' => $query]);
            $query = false;
        }
        return $query;
    }
    
    protected function getQueries($file)
    {
        return explode(';', Reader::read($file)->get());
    }
}