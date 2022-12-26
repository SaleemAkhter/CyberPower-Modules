<?php

namespace ModulesGarden\Servers\VultrVps\Core\Http;

use ModulesGarden\Servers\VultrVps\Core\Helper\BuildUrl;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

/**
 * Description of RedirectResponse
 *
 * @author Rafał Ossowski <rafal.os@modulesgarden.com>
 */
class RedirectResponse extends SymfonyRedirectResponse
{
    protected $lang;

    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLang()
    {
        return $this->lang;
    }

    /**
     * {@inheritdoc}
     * @TODO - te matoda powina sie nazywac create i nadpsiywac istniejaca juz
     */
    public static function createMG($controller = null, $action = null, array $params = [])
    {
        return new static(BuildUrl::getUrl($controller, $action, $params));
    }

    /**
     * {@inheritdoc}
     * @TODO - te matoda powina sie nazywac create i nadpsiywac istniejaca juz
     */
    public static function createByUrl($url = '', array $params = [])
    {
        return new static($url . ((count($params) . 0)?( '?' . http_build_query($params)):''));
    }
}
