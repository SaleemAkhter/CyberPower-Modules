<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;


class Ssh extends AbstractCommand
{
    const CMD_API_SSH_KEYS = 'CMD_API_SSH_KEYS';
    const CMD_SSH_KEYS = 'CMD_SSH_KEYS';
    const CMD_API_SHOW_ALL_USERS = 'CMD_API_SHOW_ALL_USERS';
    const CMD_API_DOMAIN = 'CMD_API_DOMAIN';



    /**
     * list ssh
     *
     * @param Models\Command\Ssh $ssh
     * @return mixed
     */
    public function lists()
    {

        $response = $this->curl->request(self::CMD_API_SSH_KEYS, [
            'json' => 'yes',
            'enabled_users' => 'yes'
        ]);

        return $this->loadResponse(new Models\Command\Ssh(), $response);
    }

    public function create(Models\Command\Ssh $ssh)
    {
        return $this->curl->request(self::CMD_API_SSH_KEYS, [
            'action'        => __FUNCTION__,
            'id'            => $ssh->getId(),
            'comment'       => $ssh->getComment(),
            'type'          => $ssh->getType(),
            'passwd'        => $ssh->getPassword(),
            'passwd2'       => $ssh->getPassword(),
            'keysize'       => $ssh->getKeySize(),
            'authorize'     => $ssh->getAuthorize(),
        ]);
    }

    public function delete(Models\Command\Ssh $ssh)
    {
        $post = [
            'action'        => __FUNCTION__,
            'type'          => $ssh->getType(),
        ] + $ssh->getSelect();

        return $this->curl->request(self::CMD_API_SSH_KEYS, $post);
    }

    public function authorize(Models\Command\Ssh $ssh)
    {
        $post = [
            'action'        => 'select',
            'authorize'     => 'yes',
            'tab'           => 'authorized'
        ] + $ssh->getSelect();

        return $this->curl->request(self::CMD_API_SSH_KEYS, $post);
    }

    public function modify(Models\Command\Ssh $ssh)
    {
        $postOptions = $this->setPostOption($ssh);

        $post = [
            'action'            => __FUNCTION__,
            'fingerprint'       => $ssh->getFingerprint(),
            'comment'           => $ssh->getComment(),
        ] + $postOptions;

        return $this->curl->request(self::CMD_API_SSH_KEYS, $post);
    }

    public function put(Models\Command\Ssh $ssh)
    {
        $this->curl->request(self::CMD_SSH_KEYS, [
            'action' => 'authorize',
            'text'   => $ssh->getSSHKey(),
            'json'   => 'yes',
            'type'   => 'paste'
        ]);
    }

    private function setPostOption($ssh)
    {
        $options = ['command', 'environment', 'from', 'permitopen', 'tunnel', 'no-X11-forwarding', 'no-agent-forwarding', 'no-port-forwarding', 'no-pty'];
        $postOptions = [];

        foreach ($options as $option) {
            $optionName = $option;
            if (strpos($option, '-')) {
                $optionName = str_replace('-', '', $option);
            }

            $function = 'get' . $optionName;

            if ($ssh->$function() !== null) {
                $value = $ssh->$function() == 'on' ? 'yes' : $ssh->$function();
                $postOptions[$option] = $value;
            }
        }
        return $postOptions;
    }
}
