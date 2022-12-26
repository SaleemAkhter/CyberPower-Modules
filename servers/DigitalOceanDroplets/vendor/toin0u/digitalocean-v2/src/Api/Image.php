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

use DigitalOceanV2\Entity\Action as ActionEntity;
use DigitalOceanV2\Entity\Image as ImageEntity;
use DigitalOceanV2\Exception\HttpException;

/**
 * @author Yassir Hannoun <yassir.hannoun@gmail.com>
 * @author Graham Campbell <graham@alt-three.com>
 */
class Image extends AbstractApi
{
    /**
     * @param array $criteria
     *
     * @return ImageEntity[]
     */
    public function getAll(array $criteria = [])
    {
        $page   = 1;
        $images = [];
        $baseUrl = $this->buildQuery($criteria, 200);
        do
        {
            $query    = $baseUrl . '&page=' . $page;
            $response = json_decode($this->adapter->get($query), false);
            $images[] = $response->images;
            $page++;
        } while( isset($response->links->pages->next) );

        //Optimization array_merge outside loop
        $images = array_merge(...$images);
        $this->extractMeta($response);

        return array_map(static function( $image ) {
            return new ImageEntity($image);
        }, $images);

    }
    
    private function buildQuery( array $criteria, $perPage = 200 )
    {
        $query = $this->endpoint . '/images?per_page=' . $perPage;

        if( isset($criteria['type']) && in_array($criteria['type'], ['distribution', 'application']) )
        {
            $query .= '&type=' . $criteria['type'];
        }

        if( isset($criteria['private']) && (bool)$criteria['private'] )
        {
            $query .= '&private=true';
        }

        return $query;
    }


    /**
     * @param int $id
     *
     * @return ImageEntity
     */
    public function getById($id)
    {
        $image = $this->adapter->get(sprintf('%s/images/%d', $this->endpoint, $id));

        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param string $slug
     *
     * @return ImageEntity
     */
    public function getBySlug($slug)
    {
        $image = $this->adapter->get(sprintf('%s/images/%s', $this->endpoint, $slug));

        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param int    $id
     * @param string $name
     *
     * @throws HttpException
     *
     * @return ImageEntity
     */
    public function update($id, $name)
    {
        $image = $this->adapter->put(sprintf('%s/images/%d', $this->endpoint, $id), ['name' => $name]);

        $image = json_decode($image);

        return new ImageEntity($image->image);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     */
    public function delete($id)
    {
        $this->adapter->delete(sprintf('%s/images/%d', $this->endpoint, $id));
    }

    /**
     * @param int    $id
     * @param string $regionSlug
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function transfer($id, $regionSlug)
    {
        $action = $this->adapter->post(sprintf('%s/images/%d/actions', $this->endpoint, $id), ['type' => 'transfer', 'region' => $regionSlug]);

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     *
     * @throws HttpException
     *
     * @return ActionEntity
     */
    public function convert($id)
    {
        $action = $this->adapter->post(sprintf('%s/images/%d/actions', $this->endpoint, $id), ['type' => 'convert']);

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }

    /**
     * @param int $id
     * @param int $actionId
     *
     * @return ActionEntity
     */
    public function getAction($id, $actionId)
    {
        $action = $this->adapter->get(sprintf('%s/images/%d/actions/%d', $this->endpoint, $id, $actionId));

        $action = json_decode($action);

        return new ActionEntity($action->action);
    }
}
