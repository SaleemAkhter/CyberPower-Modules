<?php

namespace ModulesGarden\Servers\AwsEc2\App\Models\SSHKey;

class SSHKeysRepository
{
    protected $modelInstance = null;
    protected $secret = null;

    public function __construct()
    {
        global $cc_encryption_hash;
        $this->secret = $cc_encryption_hash;
        $this->modelInstance = new SSHKeyModel();
    }

    public function get($serviceId = null)
    {
        $count = $this->modelInstance->where('service_id', $serviceId)->count();
        if ($count === 0)
        {
            return null;
        }

        $data = $this->modelInstance->where('service_id', $serviceId)->get();
        if (!$data)
        {
            return null;
        }

        $data = $data->first();

        $response = array();
        $response['private_key'] = $this->decode($data['private_key'], $data['salt']);
        $response['public_key'] = $this->decode($data['public_key'], $data['salt']);
        return $response;
    }

    public function add($serviceId = null, $publicKey = null, $privateKey = null, $salt = null)
    {

        $count = $this->modelInstance->where('service_id', $serviceId)->count();
        if ($count > 0)
        {
            return true;
        }
        $publicEncoded = $this->encode($publicKey, $salt);
        $privateEncoded = $this->encode($privateKey, $salt);

        return $this->modelInstance->fill(
                ['service_id' => $serviceId, 'public_key' => $publicEncoded, 'private_key' => $privateEncoded, 'salt' => $salt]
            )->save();
    }

    public function clearPrivateKey($serviceId = null)
    {
        return $this->modelInstance->where('service_id', $serviceId)->update(['private_key' => '']);
    }

    private function encode($toEncode, $salt)
    {
        return openssl_encrypt($toEncode,  'aes-256-ofb',  sha1($this->secret.$salt));
    }

    private function decode($toDecode, $salt)
    {
        return openssl_decrypt($toDecode,  'aes-256-ofb',  sha1($this->secret.$salt));
    }

}
