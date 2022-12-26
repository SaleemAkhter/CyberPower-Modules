<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Api;

use DigitalOceanV2\Entity\Projects as ProjectsEntity;
use DigitalOceanV2\Entity\Resources as ResourcesEntity;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Projects extends AbstractApi
{
    /**
     * @return ProjectsEntity[]
     */
    public function getAll()
    {
        $projects = $this->adapter->get(sprintf('%s/projects', $this->endpoint));

        $projects = json_decode($projects);
        $this->extractMeta($projects);

        return array_map(function ($project) {
            return new ProjectsEntity($project);
        }, $projects->projects);
    }

    public function assign($projectID, $resources)
    {
        $response = $this->adapter->post(sprintf('%s/projects/%s/resources', $this->endpoint, $projectID), $resources);

        $response = json_decode($response);

        return array_map(function ($project) {
            return new ResourcesEntity($project);
        }, $response->resources);
    }

}
