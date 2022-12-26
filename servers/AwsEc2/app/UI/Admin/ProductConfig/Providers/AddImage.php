<?php

namespace ModulesGarden\Servers\AwsEc2\App\UI\Admin\ProductConfig\Providers;

use ModulesGarden\Servers\AwsEc2\App\Libs\AwsIntegration\ClientWrapper;
use ModulesGarden\Servers\AwsEc2\App\Models\AvailableImages\Repository;
use ModulesGarden\Servers\AwsEc2\Core\UI\Interfaces\AdminArea;
use ModulesGarden\Servers\AwsEc2\Core\UI\ResponseTemplates\HtmlDataJsonResponse;
use ModulesGarden\Servers\AwsEc2\Core\UI\Widget\Forms\DataProviders\BaseDataProvider;

class AddImage extends BaseDataProvider implements AdminArea
{
    public function read()
    {
        $this->data['imageId'] = $this->data['imageIdDisplay'] = $this->getRequestValue('actionElementId');

        $productId = (int)$this->getRequestValue('id', 0);

        $this->data['customImageDescription'] = $this->getImageDescription($this->data['imageId'], $productId);
    }

    public function update()
    {
        $imageId = $this->formData['imageId'];
        $productId = (int)$this->getRequestValue('id', 0);
        $images = new Repository();
        $image = $images->getImage($imageId, $productId);
        if($image === null)
        {
            $response = new HtmlDataJsonResponse();
            $response->setMessageAndTranslate('imageNotFound');

            return $response->setStatusError();
        }

        $images = new Repository();
        if ($images->isDescriptionsCollision($imageId, $productId, $this->formData['customImageDescription']))
        {
            $response = new HtmlDataJsonResponse();
            $response->setMessageAndTranslate('descriptionAlreadyExists');

            return $response->setStatusError();
        }

        $images = new Repository();
        $images->updateDescription($imageId, $productId, $this->formData['customImageDescription']);

        $response = new HtmlDataJsonResponse();
        $response->setRefreshTargetIds(['selectedImages']);
        $response->setCallBackFunction('refreshAmis');

        return $response->setMessageAndTranslate('changesHasBeenSaved');
    }

    public function create()
    {
        $imageId = $this->formData['imageId'];
        $productId = (int)$this->getRequestValue('id', 0);

        $images = new Repository();
        $img = $images->getImage($imageId, $productId);
        if($img !== null)
        {
            $response = new HtmlDataJsonResponse();
            $response->setMessageAndTranslate('imageAlreadyExists');

            return $response->setStatusError();
        }

        $images = new Repository();
        if ($images->isDescriptionsCollision($imageId, $productId, $this->formData['customImageDescription']))
        {
            $response = new HtmlDataJsonResponse();
            $response->setMessageAndTranslate('descriptionAlreadyExists');

            return $response->setStatusError();
        }

        try
        {
            $awsClient = new ClientWrapper($productId);
            $list = $awsClient->getImagesListRaw(['ImageIds' => [$imageId]]);
        }
        catch (\Aws\Exception\AwsException $exc)
        {
            $response = new HtmlDataJsonResponse();
            $response->setMessage($exc->getAwsErrorMessage());

            return $response->setStatusError();
        }

        $image = $list[0];

        $images = new Repository();

        $defaultDescription = $image['Description'] ? $image['Description'] : $image['Name'];
        $customDescription = $this->formData['customImageDescription'];

        $images->addNew($imageId, $productId, ($this->isDescriptionValid($customDescription) ?
                $customDescription : $defaultDescription), $image, $image['Name'], $awsClient->getRegion());

        $response = new HtmlDataJsonResponse();
        $response->setRefreshTargetIds(['selectedImages']);
        $response->setCallBackFunction('refreshAmis');

        return $response->setMessageAndTranslate('changesHasBeenSaved');
    }

    public function delete()
    {
        $imageId = $this->formData['imageId'];
        $productId = (int)$this->getRequestValue('id', 0);

        $images = new Repository();

        $images->delete($imageId, $productId);

        $response = new HtmlDataJsonResponse();
        $response->setCallBackFunction('refreshAmis');

        return $response->setMessageAndTranslate('changesHasBeenSaved');
    }

    public function isDescriptionValid($description = null)
    {
        if (!is_string($description) || trim($description) === '')
        {
            return false;
        }

        return true;
    }

    public function getImageDescription($imageId = null, $productId = null)
    {
        $images = new Repository();
        $image = $images->getImage($imageId, $productId);
        if($image !== null)
        {
            return $image->description;
        }

        return '';
    }
}
