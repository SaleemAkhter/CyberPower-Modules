<?php

namespace ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages;

class Repository
{
    protected $modelInstance = null;

    public function __construct()
    {
        $this->modelInstance = new Model();
    }

    public function getImage($imageId = null, $productId = null)
    {
        $count = $this->modelInstance->where('image_id', $imageId)->where('product_id', $productId)->count();
        if ($count === 0)
        {
            return null;
        }

        $data = $this->modelInstance->where('image_id', $imageId)->where('product_id', $productId)->get();
        if (!$data)
        {
            return null;
        }

        return $data->first();
    }

    public function addNew($imageId = null, $productId = null, $description = '', $details = [], $name = '', $region = '')
    {
        $count = $this->modelInstance->where('image_id', $imageId)->where('product_id', $productId)->count();
        if ($count > 0)
        {
            return true;
        }

        return $this->modelInstance->fill(
                ['image_id' => $imageId, 'product_id' => $productId, 'description' => $description, 'details' => $details, 'name' => $name, 'region' => $region]
            )->save();
    }

    public function delete($imageId = null, $productId = null)
    {
        return $this->modelInstance->where('image_id', $imageId)->where('product_id', $productId)->delete();
    }

    public function getAmisForProduct($productId = 0)
    {
        $data = $this->modelInstance->where('product_id', $productId)->get();
        if (!$data)
        {
            return [];
        }

        return $data->toArray();
    }

    public function updateDescription($imageId = null, $productId = null, $description = null)
    {
        $this->modelInstance->where('product_id', $productId)->where('image_id', $imageId)
            ->update(['description' => trim($description)]);
    }

    public function isDescriptionsCollision($imageId = null, $productId = null, $description = null)
    {
        $count = $this->modelInstance->where('product_id', $productId)->where('image_id', '!=', $imageId)
                ->where('description', trim($description))->count();

        if ($count > 0)
        {
            return true;
        }

        return false;
    }

    public function getImageByName($name, $productId){
        $count = $this->modelInstance->where('name', $name)->where('product_id', $productId)->count();
        if ($count === 0)
        {
            return null;
        }

        $data = $this->modelInstance->where('name', $name)->where('product_id', $productId)->get();
        if (!$data)
        {
            return null;
        }

        return $data->first();
    }

    public function getImageByNameAndRegion($name, $region, $productId){
        $count = $this->modelInstance->where('name', $name)->where('product_id', $productId)->where('region', $region)->count();
        if ($count === 0)
        {
            return null;
        }

        $data = $this->modelInstance->where('name', $name)->where('product_id', $productId)->where('region', $region)->get();
        if (!$data)
        {
            return null;
        }

        return $data->first();
    }
}
