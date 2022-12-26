<?php

namespace ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Api;

use Exception;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Abstracts\AbstractApi;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Collection;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Base\Items\Key as KeyItem;
use ModulesGarden\Servers\DigitalOceanDroplets\App\Libs\DigitalOceanDroplets\Models\Keys\Create;

/**
 * Description of Connection
 *
 * @author Mateusz Pawłowski <mateusz.pa@modulesgarden.com>
 * 

 * 
 */
class Key extends AbstractApi
{

    public function createKey(Create $model)
    {
        $params = $model->toArray();


        return $this->api->key()->create(
                        $params['name'], $params['public_key']
        );
    }

    public function one($sshKey = null)
    {
        if (is_null($sshKey))
        {
            $sshKey = $this->client->getSshKey();
        }
        $response = $this->api->key()->getByID($sshKey);
        return new KeyItem($response, $this->api);
    }

    public function all()
    {
        $all        = $this->api->key()->getAll();
        $collection = new Collection();
        foreach ($all as $item)
        {
            $collection->add(new KeyItem($item, $this->api));
        }
        return $collection->all();
    }

    public function findID($sshKey = null)
    {
        if (is_null($sshKey))
        {
            $sshKey = $this->client->getSshKey();
        }
        $all = $this->all();
        foreach($all as $one){
            if($one->publicKey == $sshKey){
                return $one->id;
            }
        }
    }

    public function deleteKey($id = null): void
    {
        if (!is_null($id)) {
            $this->api->key()->delete($id);
        }
    }

    public function __call($name, $agruments = null)
    {
        if (method_exists($this->api->key(), $name))
        {
            return $this->api->key()->{$name}($this->client->getSshKey(), ...$agruments);
        }
        throw new Exception('Method does not exist on API.');
    }

}
