<?php
/* * ********************************************************************
*  HetznerVPS Product developed. (27.03.19)
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

namespace ModulesGarden\Servers\HetznerVps\App\Service\Sidebar;

use Illuminate\Database\Capsule\Manager as DB;
use ModulesGarden\Servers\HetznerVps\App\Enum\ConfigurableOption;
use ModulesGarden\Servers\HetznerVps\App\Repositories\Vps\ProductConfigurationRepository;
use ModulesGarden\Servers\HetznerVps\App\Service\Utility;
use ModulesGarden\Servers\HetznerVps\App\Services\LoadBalancerService;

trait ProductService
{
    /**
     * @return FileRepository
     */
    public function isoRepository()
    {
        if (!empty($this->isoRepository)) {
            return $this->isoRepository;
        }
        $storageRepository = new StorageRepository();
        $storageRepository->findByNodes([$this->vm()->getNode()])
            ->findEnabed();
        $storages = $storageRepository->fetchAsArray();
        $this->isoRepository = new FileRepository();
        $this->isoRepository->findByNodes([$this->vm()->getNode()])
            ->findByStorages($storages);
        $this->isoRepository->findIso();
        return $this->isoRepository;
    }

    /**
     * @return ProductConfigurationRepository
     */
    public function configuration()
    {
        if (!empty($this->configuration)) {
            return $this->configuration;
        }
        if (!$this->productId) {
            $this->setProductId($this->getWhmcsParamByKey("packageid"));
        }
        return $this->configuration = new ProductConfigurationRepository($this->productId);
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    public function acl()
    {
        if (!empty($this->acl)) {
            return $this->acl;
        }
        return $this->acl = new AclService();
    }

    /**
     * @return ResourceGuard
     */
    public function resourceGuard()
    {
        if (!empty($this->resourceGuard)) {
            return $this->resourceGuard;
        }
        return $this->resourceGuard = new ResourceGuard();
    }

    /**
     * @return Node
     * @throws \MGProvision\Proxmox\v2\ProxmoxApiException
     */
    public function getNode()
    {
        if ($this->node) {
            return $this->node;
        }
        if ($this->isWhmcsConfigOption(ConfigurableOption::DISK_SIZE)) {
            $diskBytes = $this->getWhmcsConfigOption(ConfigurableOption::DISK_SIZE);
            Utility::unitFormat($diskBytes, $this->configuration()->getDiskUnit(), 'bytes');
        } else {
            $diskBytes = $this->configuration()->getDiskSize();
            Utility::unitFormat($diskBytes, "gb", 'bytes');
        }
        if ($this->configuration()->isLoadBalancer()) {
            $loadBalancer = new LoadBalancerService($this->getWhmcsParamByKey('serverid'));
            $loadBalancer->setApi($this->api());
            $loadBalancerNodes = $loadBalancer->findByVmCreate();
            $nodesForUser = $loadBalancer->findNotUser($this->getWhmcsParamByKey('userid'));
            if (!$nodesForUser->isEmpty()) {
                $loadBalancerNodes = $nodesForUser;
            }
            if ($this->isWhmcsConfigOption(ConfigurableOption::MEMORY)) {
                $ram = $this->getWhmcsConfigOption(ConfigurableOption::MEMORY);
                Utility::unitFormat($ram, $this->configuration()->getMemoryUnit(), 'bytes');
            } else {
                $ram = $this->configuration()->getMemory();
                Utility::unitFormat($ram, "mb", 'bytes');
            }
            if ($this->configuration()->isQemu()) {
                $socket = $this->getWhmcsConfigOption(ConfigurableOption::SOCKETS, $this->configuration()->getSockets());
                $cores = $this->getWhmcsConfigOption(ConfigurableOption::CORES_PER_SOCKET, $this->configuration()->getCores());
                $cpu = $socket * $cores;
            } else if ($this->configuration()->isLxc()) {
                $cpu = $cores = $this->getWhmcsConfigOption(ConfigurableOption::CORES, $this->configuration()->getCores());
            }

            $node = $loadBalancerNodes->findByRam($ram)
                ->findByCpu($cpu)
                ->findByDisk($diskBytes)
                ->findByVms(1)
                ->nodeLowLoad();
            return $this->node = new Node($node);
        }
        $defaultNode = $this->configuration()->getDefaultNode();
        $nodeRepository = new NodeRepository();
        $nodeRepository->setApi($this->api());
        switch ($defaultNode) {
            case "autoNode":
                if (empty($diskBytes)) {
                    throw new \Exception("Provide disk size.");
                }
                return $this->node = $nodeRepository->findByDiskSize($diskBytes);
                break;
            case "serverNode":
                $servePrivateIP = $this->getServerPrivateIpAddress();
                $serverIp = $servePrivateIP ? $servePrivateIP : $this->getWhmcsParamByKey('serverip');
                return $this->node = $nodeRepository->findWithHostOrIp($this->getWhmcsParamByKey('serverhostname'), $serverIp);
                break;
            default:
                return $this->getDefaultNode();
        }
    }

    protected function getDefaultNode()
    {
        $defaultNode = $this->configuration()->getDefaultNode();
        $nodeRepository = new NodeRepository();
        $nodeRepository->setApi($this->api());
        switch ($defaultNode) {
            case "autoNode":
                $diskBytes = 1;
                if ($this->isWhmcsConfigOption(ConfigurableOption::DISK_SIZE)) {
                    $diskBytes = $this->getWhmcsConfigOption(ConfigurableOption::DISK_SIZE);
                    Utility::unitFormat($diskBytes, $this->configuration()->getDiskUnit(), 'bytes');
                } else {
                    $diskBytes = $this->configuration()->getDiskSize();
                    Utility::unitFormat($diskBytes, "gb", 'bytes');
                }
                if (empty($diskBytes)) {
                    throw new \Exception("Provide disk size.");
                }
                return $nodeRepository->findByDiskSize($diskBytes);
                break;
            case "serverNode":
                $servePrivateIP = $this->getServerPrivateIpAddress();
                $serverIp = $servePrivateIP ? $servePrivateIP : $this->getWhmcsParamByKey('serverip');
                return $nodeRepository->findWithHostOrIp($this->getWhmcsParamByKey('serverhostname'), $serverIp);
                break;
            default:
                return new Node($defaultNode);
        }
    }

    public function getServerPrivateIpAddress()
    {

        $serverId = $this->getWhmcsParamByKey('serverid');
        if ($serverId && $serverId > 0) {
            $serverAssignedIps = DB::table('tblservers')->where("id", $serverId)->value("assignedips");
            $serverAssignedIps = preg_replace('/\s+/', '', $serverAssignedIps);
            $serverAssignedIps = explode(PHP_EOL, $serverAssignedIps);
            return current($serverAssignedIps);
        }
    }
}