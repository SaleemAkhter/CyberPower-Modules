<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class HotlinkProtection extends AbstractCommand
{
    const CMD_HOTLINK        = 'CMD_HOTLINK';

    /**
     * list hotlink
     *
     * @return mixed
     */
    public function lists($domain)
    {
        $response = $this->curl->request(self::CMD_HOTLINK, [], [
            'json'   => 'yes',
            'ipp'    => 500,
            'domain' => $domain,
        ]);

        return $this->loadResponse(new Models\Command\HotlinkProtection(), $response);
    }

    public function save(Models\Command\HotlinkProtection $hp)
    {
        $this->curl->request(self::CMD_HOTLINK, [
            'action'                => __FUNCTION__,
            'enabled'               => $hp->isEnabled,
            'allow_blank_referer'   => $hp->getBlankRefererValue(),
            'urls'                  => $hp->getUrls(),
            'files'                 => $hp->getProtectedFiles(),
            'redirect'              => $hp->getRedirectOption(),
            'redirect_url'          => $hp->getRedirectUrl(),
            'domain'                => $hp->getDomain(),
            'json'                  => 'yes',
        ]);
    }

    public function delete($hp)
    {
        $this->curl->request(self::CMD_HOTLINK, [
            'action' => 'multiple',
            'delete' => 'yes',
            'json'   => 'yes',
            'domain'  => $hp['domain'],
            'select0' => $hp['urlToDelete']['url'],
        ]);
    }
}
