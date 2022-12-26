<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Admin\ResellerPackages\Providers;

use ModulesGarden\Servers\DirectAdminExtended\Core\Helper\Lang;
use ModulesGarden\Servers\DirectAdminExtended\App\Helpers\ProviderApi;
use ModulesGarden\Servers\DirectAdminExtended\Core\UI\ResponseTemplates;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Packages extends ProviderApi
{

    use \ModulesGarden\Servers\DirectAdminExtended\Core\Traits\Lang;

    public function read()
    {
        $this->loadLang();
        $this->data['name']                = $this->formData['name'];
        $this->data['bandwidth']                = $this->formData['bandwidth'];
        $this->availableValues['bandwidth']     = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['diskspace']            = $this->formData['diskspace'];
        $this->availableValues['diskspace'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];
        $this->data['inode']            = $this->formData['inode'];
        $this->availableValues['inode'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['vdomains']            = $this->formData['vdomains'];
        $this->availableValues['vdomains'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];
        $this->data['nsubdomains']            = $this->formData['nsubdomains'];
        $this->availableValues['nsubdomains'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['nemails']            = $this->formData['nemails'];
        $this->availableValues['nemails'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['nemailf']            = $this->formData['nemailf'];
        $this->availableValues['nemailf'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['nemailml']            = $this->formData['nemailml'];
        $this->availableValues['nemailml'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['nemailr']            = $this->formData['nemailr'];
        $this->availableValues['nemailr'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

        $this->data['mysql']            = $this->formData['mysql'];
        $this->availableValues['mysql'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];
        $this->data['domainptr']            = $this->formData['domainptr'];
        $this->availableValues['domainptr'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];
        $this->data['ftp']            = $this->formData['ftp'];
        $this->availableValues['ftp'] = [
            'unlimited' => $this->lang->absoluteTranslate('unlimited'),
            'custom'    => $this->lang->absoluteTranslate('custom')
        ];

    }

    public function create()
    {
        parent::create();

        /*Add validation for name */
        $data = [
            'name'      => $this->formData['name'],
            'bandwidth' => $this->formData['bandwidthUnlimited'] === 'on' ? 'unlimited' : $this->formData['bandwidth'],
            'quota' => $this->formData['diskspaceUnlimited'] === 'on' ? 'unlimited' : $this->formData['diskspace'],
            'inode' => $this->formData['inodeUnlimited'] === 'on' ? 'unlimited' : $this->formData['inode'],
            'vdomains' => $this->formData['vdomainsUnlimited'] === 'on' ? 'unlimited' : $this->formData['vdomains'],
            'nsubdomains' => $this->formData['nsubdomainsUnlimited'] === 'on' ? 'unlimited' : $this->formData['nsubdomains'],
            'nemails' => $this->formData['nemailsUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemails'],
            'nemailf' => $this->formData['nemailfUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailf'],
            'nemailml' => $this->formData['nemailmlUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailml'],
            'nemailr' => $this->formData['nemailrUnlimited'] === 'on' ? 'unlimited' : $this->formData['nemailr'],
            'mysql' => $this->formData['mysqlUnlimited'] === 'on' ? 'unlimited' : $this->formData['mysql'],
            'domainptr' => $this->formData['domainptrUnlimited'] === 'on' ? 'unlimited' : $this->formData['domainptr'],
            'ftp' => $this->formData['ftpUnlimited'] === 'on' ? 'unlimited' : $this->formData['ftp'],
            'nusers' => $this->formData['nusersUnlimited'] === 'on' ? '' : $this->formData['nusers'],
            'aftp'      => $this->formData['aftp'] == 'on' ? strtoupper($this->formData['aftp']) : '',
            'cgi'       => $this->formData['cgi'] == 'on' ? strtoupper($this->formData['cgi']) : '',
            'php'       => $this->formData['php'] == 'on' ? strtoupper($this->formData['php']) : '',
            'spam'      => $this->formData['spam'] == 'on' ? strtoupper($this->formData['spam']) : '',
            'catchall'  => $this->formData['catchall'] == 'on' ? strtoupper($this->formData['catchall']) : '',
            'ssl'       => $this->formData['ssl'] == 'on' ? strtoupper($this->formData['ssl']) : '',
            'ssh'       => $this->formData['ssh'] == 'on' ? strtoupper($this->formData['ssh']) : '',
            'userssh'       => $this->formData['userssh'] == 'on' ? strtoupper($this->formData['userssh']) : '',
            'oversell'       => $this->formData['oversell'] == 'on' ? strtoupper($this->formData['oversell']) : '',
            'cron'      => $this->formData['cron'] == 'on' ? strtoupper($this->formData['cron']) : '',
            'sysinfo'   => $this->formData['sysinfo'] == 'on' ? strtoupper($this->formData['sysinfo']) : '',
            'login_keys'=> $this->formData['login_keys'] == 'on' ? strtoupper($this->formData['login_keys']) : '',
            'dnscontrol'=> $this->formData['dnscontrol'] == 'on' ? strtoupper($this->formData['dnscontrol']) : '',
            'suspend_at_limit'       => $this->formData['suspend_at_limit'] == 'on' ? strtoupper($this->formData['suspend_at_limit']) : '',
            'serverIp'       => $this->formData['serverip'] == 'on' ? strtoupper($this->formData['serverip']) : ''

        ];
        if($this->formData['diskspace'] === 'off')
        {
            $data['quota'] = $this->formData['diskspaceCustom'];
        }else{
            $data['uquota'] = $this->formData['quota'];
        }
        if($this->formData['nusersUnlimited'] === 'on')
        {
            $data['unusers'] ="yes";
        }

        $this->loadAdminApi();
        $response=$this->adminApi->adminPackage->createResellerPackage($data);
        if($this->getProductConfiguration('package') != "custom" || $this->getProductConfiguration('dnscontrol') == "on")
        {
            $data['localMail'] = $this->formData['localMail'] == 'on' ? $this->formData['localMail'] : 'off';
            $this->userApi->domain->setLocalMail(new Models\Command\Domain($data));
        }


        return (new ResponseTemplates\RawDataJsonResponse([
            'url' => $this->getPackagesIndexURL(),
        ]))->setMessageAndTranslate('packageHasBeenCreated')->setCallBackFunction('redirectAfterFormSubmit');
    }

    public function delete()
    {
        parent::delete();
        $this->loadAdminApi();
        $data = [
            'name' => $this->formData['package']
        ];
        $response=$this->adminApi->adminPackage->deleteResellerPackage(new Models\Command\ResellerPackage($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('packageHasBeenDeleted');
    }

    public function deleteMany()
    {
        parent::delete();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        $this->loadLang();
        foreach ($domainsName as $name)
        {
            if($name === $this->getWhmcsParamByKey('domain'))
            {
                return (new ResponseTemplates\RawDataJsonResponse())
                ->setStatusError()
                ->setMessage($this->lang->addReplacementConstant('domain',$name)->absoluteTranslate('packageCannotBeDeleted'));
            }
            $data[] = new Models\Command\Domain(['name' => $name]);
        }
        $this->userApi->domain->deleteMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('packagesHaveBeenDeleted');
    }

    public function update()
    {

    }

    public function reload()
    {
        $this->data['name']                   = (is_null($this->formData['name'])) ? $this->data['name'] : $this->formData['name'] ;
        $this->data['bandwidth']                = (is_null($this->formData['bandwidth'])) ? $this->data['bandwidth'] : $this->formData['bandwidth'];
        $this->data['bandwidthCustom']          = (is_null($this->formData['bandwidthCustom'])) ? $this->data['bandwidthCustom'] : $this->formData['bandwidthCustom'];
        $this->data['diskspace']                = (is_null($this->formData['diskspace'])) ? $this->data['diskspace'] : $this->formData['diskspace'];
        $this->data['diskspaceCustom']          = (is_null($this->formData['diskspaceCustom'])) ? $this->data['diskspaceCustom'] : $this->formData['diskspaceCustom'];
        $this->data['Custom']          = (is_null($this->formData['Custom'])) ? $this->data['Custom'] : $this->formData['Custom'];


        $this->data['inode']                = (is_null($this->formData['inode'])) ? $this->data['inode'] : $this->formData['inode'];
        $this->data['inodeCustom']          = (is_null($this->formData['inodeCustom'])) ? $this->data['inodeCustom'] : $this->formData['inodeCustom'];

        $this->data['vdomains']          = (is_null($this->formData['vdomains'])) ? $this->data['vdomains'] : $this->formData['vdomains'];
        $this->data['vdomainsCustom']          = (is_null($this->formData['vdomainsCustom'])) ? $this->data['vdomainsCustom'] : $this->formData['vdomainsCustom'];



        $this->data['nsubdomains']          = (is_null($this->formData['nsubdomains'])) ? $this->data['nsubdomains'] : $this->formData['nsubdomains'];
        $this->data['nsubdomainsCustom']          = (is_null($this->formData['nsubdomainsCustom'])) ? $this->data['nsubdomainsCustom'] : $this->formData['nsubdomainsCustom'];



        $this->data['nemails']          = (is_null($this->formData['nemails'])) ? $this->data['nemails'] : $this->formData['nemails'];
        $this->data['nemailsCustom']          = (is_null($this->formData['nemailsCustom'])) ? $this->data['nemailsCustom'] : $this->formData['nemailsCustom'];



        $this->data['nemailf']          = (is_null($this->formData['nemailf'])) ? $this->data['nemailf'] : $this->formData['nemailf'];
        $this->data['nemailfCustom']          = (is_null($this->formData['nemailfCustom'])) ? $this->data['nemailfCustom'] : $this->formData['nemailfCustom'];


        $this->data['nemailml']          = (is_null($this->formData['nemailml'])) ? $this->data['nemailml'] : $this->formData['nemailml'];
        $this->data['nemailmlCustom']    = (is_null($this->formData['nemailmlCustom'])) ? $this->data['nemailmlCustom'] : $this->formData['nemailmlCustom'];


        $this->data['nemailr']          = (is_null($this->formData['nemailr'])) ? $this->data['nemailr'] : $this->formData['nemailr'];
        $this->data['nemailrCustom']    = (is_null($this->formData['nemailrCustom'])) ? $this->data['nemailrCustom'] : $this->formData['nemailrCustom'];


        $this->data['mysql']          = (is_null($this->formData['mysql'])) ? $this->data['mysql'] : $this->formData['mysql'];
        $this->data['mysqlCustom']    = (is_null($this->formData['mysqlCustom'])) ? $this->data['mysqlCustom'] : $this->formData['mysqlCustom'];


        $this->data['domainptr']          = (is_null($this->formData['domainptr'])) ? $this->data['domainptr'] : $this->formData['domainptr'];
        $this->data['domainptrCustom']    = (is_null($this->formData['domainptrCustom'])) ? $this->data['domainptrCustom'] : $this->formData['domainptrCustom'];


        $this->data['ftp']                      = (is_null($this->formData['ftp'])) ? $this->data['ftp'] : $this->formData['ftp'];

        $this->data['ftpCustom']          = (is_null($this->formData['ftpCustom'])) ? $this->data['ftpCustom'] : $this->formData['ftpCustom'];

        $this->data['ssl']                      = (is_null($this->formData['ssl'])) ? $this->data['ssl'] : $this->formData['ssl'];
        $this->data['cgi']                      = (is_null($this->formData['cgi'])) ? $this->data['cgi'] : $this->formData['cgi'];
        $this->data['php']                      = (is_null($this->formData['php'])) ? $this->data['php'] : $this->formData['php'];
        $this->data['spam']                     = (is_null($this->formData['spam'])) ? $this->data['spam'] : $this->formData['spam'];
        $this->data['catchall']                 = (is_null($this->formData['catchall'])) ? $this->data['catchall'] : $this->formData['catchall'];

        $this->data['ssh']                      = (is_null($this->formData['ssh'])) ? $this->data['ssh'] : $this->formData['ssh'];

        $this->data['jail']                = (is_null($this->formData['jail'])) ? $this->data['jail'] : $this->formData['jail'];
        $this->data['cron']                = (is_null($this->formData['cron'])) ? $this->data['cron'] : $this->formData['cron'];
        $this->data['dnscontrol']                = (is_null($this->formData['dnscontrol'])) ? $this->data['dnscontrol'] : $this->formData['dnscontrol'];
        $this->data['suspend_at_limit']                = (is_null($this->formData['suspend_at_limit'])) ? $this->data['suspend_at_limit'] : $this->formData['suspend_at_limit'];
    }

    public function suspendUnsuspend()
    {
        parent::suspendUnsuspend();

        $data = [
            'name' => $this->formData['domain']
        ];

        $this->userApi->domain->suspendUnsuspend(new Models\Command\Domain($data));

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('singleToggleSuspend');
    }

    public function suspendUnsuspendMany()
    {
        parent::suspendUnsuspend();

        $data        = [];
        $domainsName = $this->getRequestValue('massActions', []);
        foreach ($domainsName as $name) {
            $data[] = new Models\Command\Domain([
                'name' => $name
            ]);
        }

        $this->userApi->domain->suspendUnsuspendMany($data);

        return (new ResponseTemplates\RawDataJsonResponse())->setMessageAndTranslate('multipleToggleSuspend');
    }
    protected function getPackagesIndexURL()
    {
        $params = [
            'action' => 'productdetails',
            'id'     =>   $this->getRequestValue('id', false),
            'modop'     => 'custom',
            'a'     => 'management',
            'mg-page'     => 'ResellerPackages',
        ];

        return 'clientarea.php?'. \http_build_query($params);

    }
}
