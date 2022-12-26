<?php

namespace ModulesGarden\Servers\HetznerVps\App\Libs\HetznerVps\Api\Repositories;
use LKDev\HetznerCloud\Clients\GuzzleClient;
use LKDev\HetznerCloud\HetznerAPIClient;
use LKDev\HetznerCloud\Models\Images\Image;
use LKDev\HetznerCloud\Models\Images\Images;


class SnapshotRepository extends Images
{

    private  $filter=[];
    protected $fetch=[];
    private $filterByServerId = null;

    public function __construct(GuzzleClient $httpClient = null)
    {
        parent::__construct($httpClient);
        $this->filter['type'] = 'snapshot';
    }


    public function findByServerId($serverId){
        if(is_null($serverId)){
            throw new \InvalidArgumentException("Server ID cannot be empty");
        }
        $this->filterByServerId =   $serverId;
        return $this;
    }

    /**
     * @return Image[]
     * @throws \LKDev\HetznerCloud\APIException
     */
    public function fetch(){
        if($this->fetch){
            return $this->fetch;
        }
        $request = http_build_query($this->filter);
        $response = $this->httpClient->get('images?' . $request);
        if (HetznerAPIClient::hasError($response)) {
           return null;
        }
        $this->fetch = [];
        foreach ( self::parse(json_decode((string)$response->getBody())->images)->images as $item){
            if( $this->filterByServerId &&  $item->createdFrom->id != $this->filterByServerId ){
                continue;
            }
            $this->fetch[] = $item;
        }
        return $this->fetch;
    }

    public function  getImageSize(){
        $size = 0;
        foreach ($this->fetch() as $entity) {
            $size += $entity->imageSize;
        }
        return $size;
    }

    public function delete(){

        foreach ($this->fetch() as $entity) {
            $entity->delete();
        }
        $this->fetch=[];

    }

}