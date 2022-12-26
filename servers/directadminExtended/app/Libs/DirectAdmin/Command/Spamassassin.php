<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Spamassassin extends AbstractCommand
{
    const CMD_SPAMASSASSIN  = 'CMD_API_SPAMASSASSIN';

    /**
     * get spamassassin settings
     *
     * @param Models\Command\Spamassassin $spamassassin
     * @return mixed
     */
    public function get(Models\Command\Spamassassin $spamassassin)
    {
        $response = $this->curl->request(self::CMD_SPAMASSASSIN, [],[
            'domain' => $spamassassin->getDomain(),
        ]);

        return $this->loadResponse(new Models\Command\Spamassassin(), $response);
    }

    /**
     * save spamassassin settings
     *
     * @param Models\Command\Spamassassin $spamassassin
     * @return mixed
     */
    public function save(Models\Command\Spamassassin $spamassassin)
    {
        $info = $this->curl->request(self::CMD_SPAMASSASSIN, $spamassassin->getAPIData(), null, true);
    }

    /**
     * disable spamassassin
     *
     * @param Models\Command\Spamassassin $spamassassin
     * @return mixed
     */
    public function disable(Models\Command\Spamassassin $spamassassin)
    {
        return $this->curl->request(self::CMD_SPAMASSASSIN, [
            'action' => __FUNCTION__,
            'domain' => $spamassassin->getDomain()
        ]);
    }
}
