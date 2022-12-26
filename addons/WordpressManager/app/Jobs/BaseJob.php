<?php


namespace ModulesGarden\WordpressManager\App\Jobs;


use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\ProductSetting;
use ModulesGarden\WordpressManager\Core\Queue\Job;

class BaseJob extends Job
{
    use BaseClientController;
    /**
     * @var ProductSetting
     */
    protected $productSetting;
    protected $params;

    protected function getModelData()
    {
        return unserialize($this->model->data);
    }

    protected function putModelDataAndSave(array $newData)
    {

        $data = $this->getModelData();
        $newData +=$data;
        $this->setModelDataAndSave($newData);
        return $this;
    }

    protected function setModelDataAndSave(array $data)
    {
        $this->model->data = serialize($data);
        $this->model->save();
        return $this;
    }

    protected function sleep($minutes =1){
        $this->model->setWaiting();
        $this->model->setRetryAfter(date("Y-m-d H:i:s", strtotime("+{$minutes} minute")));
        $this->model->increaseRetryCount();
    }
}