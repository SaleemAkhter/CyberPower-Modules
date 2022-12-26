<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Dedicated;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;

/**
 * Class Server
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method model()
 * @method mrtg()
 * @method ips($params)
 * @method features()
 * @method terminate()
 * @method install()
 * @method reboot()
 * @method start()
 * @method boot()
 * @method update($params)
 * @method makeBoot($bootType)
 */
class Server extends BaseRepository
{
    /**model
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Dedicated\Server
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($id, $params = [])
    {
        parent::__construct($params);
        $this->item = $this->api->dedicated->server()->one($id);
        $this->methods = get_class_methods($this->item);
    }

    public function getBackupInformation($id)
    {
        return $this->api->dedicated->server()->one($id)->features()->backupFTP()->getInfo();
    }
}