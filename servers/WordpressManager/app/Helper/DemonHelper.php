<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\App\Models\Doe\DemonTask;
use ModulesGarden\WordpressManager\Core\Models\Whmcs\DomainPricing;

/**
 * Description of DemonHelper
 *
 * @author Rafał Ossowski <rafal.so@modulesgarden.com>
 */
class DemonHelper
{
    /**
     * @var DemonTask
     */
    protected $demonTask;

    /**
     * @var DomainPricing
     */
    protected $domainPricing;

    public function __construct(DemonTask $demonTask, DomainPricing $domainPricing)
    {
        $this->demonTask     = $demonTask;
        $this->domainPricing = $domainPricing;
    }

    public function getReadyTask($sessionId)
    {
        $return = $this->demonTask
                ->select(
                        $this->demonTask->getTable() . ".domain_status as status", $this->domainPricing->getTable() . ".id as id"
                )->WithDomainPrincing()
                ->WithSessionId($sessionId)
                ->StatusReady()
                ->whereNull($this->demonTask->getTable() . '.deleted_at')
                ->orderBy("{$this->demonTask->getTable()}.created_at", "ASC")
                ->get()
                ->toArray();

        $this->demonTask->WithSessionId($sessionId)
                ->StatusReady()
                ->whereNull($this->demonTask->getTable() . '.deleted_at')
                ->orderBy("{$this->demonTask->getTable()}.created_at", "ASC")
                ->update(['deleted_at' => time()]);

        return $return;
    }
}
