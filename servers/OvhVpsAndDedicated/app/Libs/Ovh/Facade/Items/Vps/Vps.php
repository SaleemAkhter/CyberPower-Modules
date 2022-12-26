<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Items\Vps;

use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\Base;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Facade\BaseRepository;
use ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Models\Vps\Vps as VpsModel;


/**
 * Class Vps
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 *
 * @method start()
 * @method stop()
 * @method status()
 * @method terminate()
 * @method serviceInfos()
 * @method reboot()
 * @method setPassword()
 * @method availableUpgrade()
 * @method datacenter()
 * @method getConsoleUrl()
 * @method ipCountryAvailable()
 * @method monitoring()
 * @method backupftp()
 * @method disks()
 * @method model()
 * @method distribution()
 * @method ips()
 * @method option()
 * @method secondaryDnsDomains()
 * @method snapshot()
 * @method tasks()
 * @method templates()
 * @method veeam()
 * @method secondaryDnsNameServerAvailable()
 * @method openConsoleAccess($params = [])
 * @method reinstall($params = [])
 * @method changeContact($params = [])
 * @method confirmTermination($params = [])
 * @method createSnapshot($params = [])
 * @method usage($type = [])
 * @method update($params = [])
 * @method rescue()
 * @method unrescue()
 *
 */
class Vps extends BaseRepository
{
    /**
     * @var \ModulesGarden\Servers\OvhVpsAndDedicated\App\Libs\Ovh\Service\Api\Items\Vps\Vps
     */
    protected $item;

    /**
     * @var array
     */
    protected $methods;

    public function __construct($id, $params = [])
    {
        parent::__construct($params);
        $this->item = $this->api->vps->one($id);
        $this->methods = get_class_methods($this->item);
    }
}
