<?php

namespace ModulesGarden\Servers\VultrVps\Core\Queue\Job;

use function ModulesGarden\Servers\VultrVps\Core\Helper\queue;

/**
 * Trait ChildrenTrait
 * @package ModulesGarden\Servers\VultrVps\Core\Queue\Children
 * @version 1.0.0
 */
trait ChildrenTrait
{
    /**
     * @param $job
     * @param $arguments
     * @param null $relType
     * @param null $relId
     * @param null $customId
     */
    protected function addChildToQueue($job, $arguments, $relType = null, $relId = null, $customId = null)
    {
        queue($job, $arguments, $this->model->id, $relType, $relId, $customId);
    }
}