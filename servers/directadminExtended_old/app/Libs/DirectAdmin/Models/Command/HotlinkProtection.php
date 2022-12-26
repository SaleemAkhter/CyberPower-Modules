<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Interfaces\ResponseLoad;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models\AbstractModel;

class HotlinkProtection extends AbstractModel implements ResponseLoad
{
    public $urls;
    public $isEnabled;
    public $blankReferer;
    public $protectedFiles;
    public $redirectTo;
    public $redirectUrl;
    public $domain;

    public function getUrls()
    {
        return $this->urls;
    }

    public function setUrls($response)
    {

        foreach ($response as $obj) {
            $urls[] = [
                'url' => $obj->url
            ];
        }

        $this->urls = $urls;
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setRedirectUrl(string $url = '')
    {
        $this->redirectUrl = $url;
        return $this;
    }

    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    public function setRedirectOption($value)
    {
        $this->redirectTo = $value;
        return $this;
    }

    public function getRedirectOption()
    {
        return $this->redirectTo;
    }

    public function setProtectedFiles(string $filesExtensions)
    {
        $this->protectedFiles = $filesExtensions;

        return $this;
    }

    public function getProtectedFiles()
    {
        return $this->protectedFiles;
    }

    public function setBlankRefererValue(string $response = '')
    {
        $this->blankReferer = $response;
        return $this;
    }

    public function getBlankRefererValue()
    {
        return $this->blankReferer;
    }

    public function loadResponse($response, $function = null)
    {

        $this->setBlankRefererValue($response->options->HOTLINK_ALLOW_BLANK_REFERER_CHECKED);
        $this->setRedirectOption($response->options->REDIRECT_TO_URL_CHECKED);
        $this->setProtectedFiles($response->options->HOTLINK_PROTECT_FILES);
        $this->setUrls($response->data);
        $this->setRedirectUrl($response->options->REDIRECT_URL);
        $this->isEnabled = ($response->options->HOTLINK_ENALBED_CHECKED);

        $self = new self(['urls' => $this->getUrls()]);
        $this->addResponseElement($self);

        return $this;
    }
}
