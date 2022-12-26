<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Applications\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;

class ApplicationsEdit extends Applications
{
    public function read()
    {
        parent::loadApplicationAPI();

        $this->data['id'] = $this->actionElementId;

        $installation = $this->api->getInstalation($this->data['id']);
        $installation = $this->convertToArray($installation);

        $this->setData($installation);
    }

    public function update()
    {
        $this->loadApplicationAPI();

        $this->api->installationEdit($this->formData['id'], $this->formData);
        return (new ResponseTemplates\HtmlDataJsonResponse())->setMessageAndTranslate('applicationHasBeenEdited');
    }

    private function convertToArray($data)
    {
        if (is_array($data) || is_object($data)) {

            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->convertToArray($value);
            }
            return $result;
        }

        return $data;
    }
}