<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Connections;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Models\Client;
/**
 * Class Connection
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
abstract class Base
{
    /*
     * @var object $params
     */

    protected $client;

    /*
     * @var
     */
    protected $connection;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->setConnection();
    }

    /*
     * Get API Connection details
     *
     */
    public function getConnection()
    {
        if($this->connection)
        {
            return $this->connection;
        }
        $this->setConnection();
        return $this->connection;
    }
}