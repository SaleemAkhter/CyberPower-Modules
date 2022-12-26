<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018-07-17
 * Time: 13:11
 */

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Command;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\DirectAdmin\Models;

class Ssl extends AbstractCommand
{
    const CMD_SSL = 'CMD_API_SSL';
    const CMD_SLL_V2 = 'CMD_SSL';

    /**
     * list ssl certificates
     *
     * @param Models\Command\Ssl $ssl
     * @return mixed
     */
    public function lists(Models\Command\Ssl $ssl)
    {
        $response = $this->curl->request(self::CMD_SSL, [], [
            'domain' => $ssl->getDomain(),
            'get'    => 1,
            'dnsproviders'=>'yes',
            'tab'=>'paste',
            'ipp'=>50,
            'json'=>'yes'
        ]);
        $response=json_decode(json_encode($response),true);
        foreach ($response as $key => $value) {
            unset($response[$key]);
            $response[str_replace(" ", "_",$key)]=$value;
        }

        return $this->loadResponse(new Models\Command\Ssl(), $response);
    }
    /**
     * list ssl certificates
     *
     * @param Models\Command\Ssl $ssl
     * @return mixed
     */
    public function sslOptions(Models\Command\Ssl $ssl)
    {
        $response = $this->curl->request(self::CMD_SSL, [], [
            'domain' => $ssl->getDomain(),
            'get'    => 1,
            'dnsproviders'=>'yes',
            'tab'=>'paste',
            'ipp'=>50,
            'json'=>'yes'
        ]);
        return json_decode(json_encode($response),true);
    }

    /**
     * save certificate
     *
     * @param Models\Command\Ssl $ssl
     * @return mixed
     */
    public function save(Models\Command\Ssl $ssl)
    {
        return $this->curl->request(self::CMD_SSL, [
            'action'        => __FUNCTION__,
            'domain'        => $ssl->getDomain(),
            'type'          => $ssl->getType(),
            'country'       => $ssl->getCountry(),
            'company'       => $ssl->getCompany(),
            'division'      => $ssl->getDivision(),
            'email'         => $ssl->getEmail(),
            'province'      => $ssl->getProvince(),
            'city'          => $ssl->getCity(),
            'keysize'       => $ssl->getKeysize(),
            'certificate'   => $ssl->getCertificate(),
            'request'       => $ssl->getRequest(),
            'name'          => $ssl->getName()
        ]);
    }

    public function upload(Models\Command\Ssl $ssl)
    {
        return $this->curl->request(self::CMD_SSL, [
            'action'        => 'save',
            'domain'        => $ssl->getDomain(),
            'type'          => 'paste',
            'certificate'   => $ssl->getCertificate(),
        ], null, true);
    }

    public function letsEncryptSaveProvider($data)
    {


        return $this->curl->request(self::CMD_SLL_V2, $data);
    }
    public function letsEncryptForceSsl($data)
    {


        return $this->curl->request("CMD_DOMAIN", $data);
    }

    public function letsEncrypt(Models\Command\Ssl $ssl)
    {
        $data = [
            'domain'        => $ssl->getDomain(),
            'wildcard'      => $ssl->getWildcard(),
            'action'        => 'save',
            'type'          => $ssl->getType(),
            'request'       => $ssl->getRequest(),
            'name'          => $ssl->getName(),
            'email'         => $ssl->getEmail(),
            'keysize'       => $ssl->getKeysize(),
            'encryption'    => $ssl->getEncryption(),
            'json'          => 'yes',
            'background'    => 'auto'
        ];

        if ($ssl->getWildcard() === 'yes') {
            $entries = [
                'le_wc_select0' => $ssl->getDomain(),
                'le_wc_select1' => '*.' . $ssl->getDomain()
            ];
            $data = array_merge($data, $entries);
        } else {
            $data = array_merge($data, $ssl->getEntries());
        }

        return $this->curl->request(self::CMD_SLL_V2, $data);
    }

    public function loadAvailableOptions(Models\Command\Ssl $ssl)
    {
        $response = $this->curl->request(self::CMD_SLL_V2, null, [
            'json' => 'yes',
            'domain' => $ssl->getDomain(),
            'tab' => 'letsencrypt'
        ], false, true);

        $model = new Models\Command\Ssl(['encryptOptions' => (array)$response->LETSENCRYPT_OPTIONS]);

        return $model->getEncryptOptions();
    }
}
