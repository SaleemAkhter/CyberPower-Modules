<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Ssh\Providers;

use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use function ModulesGarden\Servers\DirectAdminExtended\Core\Helper\di;


class SshProvider extends ProviderApi
{
    public function read()
    {
        parent::read();

        $values = stripslashes(html_entity_decode($this->actionElementId));
        $values = json_decode($values, true);


        $this->data['fingerprint']              = $values['fingerprint'];
        $this->data['hiddenFingerprint']        = $values['fingerprint'];
        $this->data['type']                     = $values['type'];
        $this->data['hiddentype']               = $values['type'];
        $this->data['keysize']                  = $values['keysize'];
        $this->data['hiddenkeysize']            = $values['keysize'];
        $this->data['comment']                  = $values['comment'];
        $this->data['kind']                     = $values['kind'];

        if ($values['options']) {
            foreach ($values['options'] as $option) {
                $this->data[$option['name']] = $option['value'] ? $option['value'] : 'on';
            }
        }


        /* @var $lang \ModulesGarden\Servers\DirectAdminExtended\Core\Lang\Lang */
        $lang = di('lang');

        $options = [
            'command' => $lang->T('ssh', 'options', 'command'),
            'environment' => $lang->T('ssh', 'options', 'environment'),
            'from' => $lang->T('ssh', 'options', 'from'),
            'no-X11-forwarding' => $lang->T('ssh', 'options', 'no-X11-forwarding'),
            'no-agent-forwarding' => $lang->T('ssh', 'options', 'no-agent-forwarding'),
            'no-port-forwarding' => $lang->T('ssh', 'options', 'no-port-forwarding'),
            'no-pty' => $lang->T('ssh', 'options', 'no-pty'),
            'permitopen' => $lang->T('ssh', 'options', 'permitopen'),
            'tunnel' => $lang->T('ssh', 'options', 'tunnel'),
        ];
        $this->availableValues['options'] = $options;

        $keySize = [
            '1024' => '1024',
            '2048' => '2048',
            '4096' => '4096',
        ];
        $this->availableValues['keySize'] = $keySize;

        if ($this->formData) {
            $this->data['comment']              = $this->formData['comment'];

            $this->data['environment']          = $this->formData['environment'];
            $this->data['command']              = $this->formData['command'];
            $this->data['from']                 = $this->formData['from'];
            $this->data['tunnel']               = $this->formData['tunnel'];
            $this->data['no-X11-forwarding']    = $this->formData['no-X11-forwarding'];
            $this->data['no-agent-forwarding']  = $this->formData['no-agent-forwarding'];
            $this->data['no-port-forwarding']   = $this->formData['no-port-forwarding'];
            $this->data['permitopen']           = $this->formData['permitopen'];
        }
    }

    public function create()
    {
        parent::create();

        $data = [
            'id'        => $this->formData['keyid'],
            'comment'   => $this->formData['comment'],
            'password'  => $this->formData['SSHPassword'],
            'type'      => $this->formData['type'],
            'keysize'   => $this->formData['keySize'],
            'authorize' => $this->formData['authorize'] == 'on' ? 'yes' : 'no',
        ];

        $this->userApi->ssh->create(new Models\Command\Ssh($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('SshKeyHasBeenCreated')
            ->setRefreshTargetIds(['publicSshTable', 'authorizeSshTable']);
    }

    public function delete()
    {
        parent::delete();

        $data = [
            'type'       => $this->formData['kind'],
            'select'     => [
                'select0' => $this->formData['fingerprint'],
            ]
        ];
        $message = 'SshKeyHasBeenDeleted';

        foreach ($this->getMassActionsValues() as $key => $value) {
            $value = stripslashes(html_entity_decode($value));
            $value = json_decode($value);

            $data['type'] = $value->kind;
            $data['select']['select' . $key] = $value->fingerprint;
        }
        if ($this->getRequestValue('massActions')) {
            $message = 'SshKeysHasBeenDeleted';
        }

        $this->userApi->ssh->delete(new Models\Command\Ssh($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setRefreshTargetIds(['publicSshTable', 'authorizeSshTable'])
            ->setMessageAndTranslate($message);
    }

    public function update()
    {
        parent::update();

        $options = [];
        foreach ($this->formData['options'] as $option) {
            $optionName = $option;

            if (strpos($option, '-')) {
                $optionName = str_replace('-', '', $option);
            }

            $options[$optionName] = $this->formData[$option];
        }

        $data = [
            'global' => $this->formData['global_key'] == 'on' ? 'yes' : 'no',
            'fingerprint' => $this->formData['hiddenFingerprint'],
            'comment' => $this->formData['comment'],
        ] + $options;

        $this->userApi->ssh->modify(new Models\Command\Ssh($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate('SshKeyHasBeenUpdated');
    }

    public function authorize()
    {
        parent::update();
        $data = [];
        if ($this->getRequestValue('massActions')) {
            foreach ($this->getMassActionsValues() as $key => $values) {
                $values = stripslashes(html_entity_decode($values));
                $values = json_decode($values);

                $data['select']['select' . $key] = $values->fingerprint;
            }
            $message = 'SshKeysHasBeenAuthorized';
        } else {
            $data['select']['select0'] = $this->formData['fingerprint'];
            $message = 'SshKeyHasBeenAuthorized';
        }

        $this->userApi->ssh->authorize(new Models\Command\Ssh($data));

        return (new ResponseTemplates\HtmlDataJsonResponse())
            ->setMessageAndTranslate($message)
            ->setRefreshTargetIds(['publicSshTable', 'authorizeSshTable']);
    }

    public function put()
    {
        parent::put();

        $data = [
            'sshKey' => $this->formData['sshKey'],
        ];

        $this->userApi->ssh->put(new Models\Command\Ssh($data));
        $message = 'SshKeyHasBeenAuthorized';

        return (new ResponseTemplates\HtmlDataJsonResponse())
        ->setMessageAndTranslate($message)
        ->setRefreshTargetIds(['publicSshTable', 'authorizeSshTable']);
    }
}
