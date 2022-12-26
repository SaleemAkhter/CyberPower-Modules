<?php

namespace ModulesGarden\Servers\HetznerVps\Core\Queue;

/**
 * Interface QueueInterface
 * @package ModulesGarden\Servers\HetznerVps\Core\Queue
 */
interface QueueInterface
{
    /**
     * @param $job
     * @param string $data
     * @param int $parentId
     * @param null $relType
     * @param null $relId
     * @param null $customId
     * @return mixed
     */
    public function push($job, $data = '', $parentId = 0, $relType = null, $relId = null, $customId = null);

    /**
     * @return mixed
     */
    public function pop();

    /**
     * @return mixed
     */
    public function count();
}