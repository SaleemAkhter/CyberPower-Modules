<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use ModulesGarden\WordpressManager\App\Http\Client\BaseClientController;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\WordpressManager\App\Repositories\ProductSettingRepository;

class InstallationSettings
{
    use RequestObjectHandler;
    use BaseClientController;

    public function __construct()
    {
        $this->loadRequestObj();
    }

    public function getWordpressSettings()
    {
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
            ->setUserId($this->request->getSession('uid'));

        $repository               = new ProductSettingRepository;
        $model                    = $repository->forProductId($this->getInstallation()->hosting->product->id);
        $this->data               = $model->toArray();

        return $this->data;
    }

    public function getInstallation()
    {
        $this->reset();
        $this->setInstallationId($this->request->get('wpid'))
            ->setUserId($this->request->getSession('uid'));

        return $this->installation = Installation::where("user_id", $this->userId)
            ->where("id", $this->installationId)
            ->firstOrFail();
    }
}