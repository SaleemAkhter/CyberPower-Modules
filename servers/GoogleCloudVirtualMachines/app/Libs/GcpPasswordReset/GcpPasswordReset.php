<?php

namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Libs\GcpPasswordReset;


use ModulesGarden\Servers\GoogleCloudVirtualMachines\Core\Models\Whmcs\Hosting;


class GcpPasswordReset
{

    private $instance;
    private $compute;
    private $project;
    private $email;

    public function __construct($instance, $compute, $project, $email)
    {
        $this->instance = $instance;
        $this->compute = $compute;
        $this->project = $project;
        $this->email = $email;

    }

    public function getNewPassword(int $sleep = 10): String
    {

        $instanceData =  $this->compute->instances->get(
            $this->project,
            $this->instance->getZone(),
            $this->instance->getId()
        );
        $metadata = $instanceData->getMetadata();
        $key = openssl_pkey_new();

        $modulusExponent = $this->getModulusExponentInBase64($key);
        $keys = $this->getJsonString($modulusExponent['modulus'], $modulusExponent['exponent']);
        $newMetadata = $this->getUpdatedWindowsKeys($metadata, $keys);
        $this->updateInstanceMetadata($newMetadata);

        sleep($sleep);

        $decodedOutput = $this->getEncryptedPasswordFromSerialPort();

        return $this->decryptPassword($decodedOutput, $key);
    }

    public function setNewPasswordForWhmcsOrder(int $productId, string $password): void
    {
        $model = Hosting::find($productId);

        $model->password = encrypt($password);

        $model->save();
    }

    private function getModulusExponentInBase64($key): array
    {
        $data = openssl_pkey_get_private($key);
        $data = openssl_pkey_get_details($data);

        $modulus = base64_encode($data['rsa']['n']);
        $exponent = base64_encode($data['rsa']['e']);

        return [
            'modulus' => $modulus,
            'exponent' => $exponent
        ];
    }

    private function getJsonString(string $modulus, string $exponent): string
    {
        $date = $this->getExpirationTimeString();

        return json_encode([
            'modulus' => $modulus,
            'exponent' => $exponent,
            'userName' => 'admin',
            'email' => $this->email,
            'expireOn' => $date
        ]);
    }

    private function getUpdatedWindowsKeys(\Google_Service_Compute_Metadata $oldMetadata, string $keys): \Google_Service_Compute_Metadata
    {
        $object = new \stdClass;
        $object->key = "windows-keys";
        $object->value = $keys;

        $newMetadata = new \Google_Service_Compute_Metadata();
        $newMetadata->setFingerprint($oldMetadata->getFingerprint());
        $newMetadata->setItems([
            $object
        ]);

        return $newMetadata;
    }

    private function updateInstanceMetadata($newMetadata): void
    {
        $this->compute->instances->setMetadata(
            $this->project,
            $this->instance->getZone(),
            $this->instance->getId(),
            $newMetadata
        );
    }

    private function getEncryptedPasswordFromSerialPort(): \stdClass
    {
        
        $destdir = './modules/servers/GoogleCloudVirtualMachines/app/Http/Actions/';
       
         

        // //  Operation
            for($i =0; $i  < 240; $i++){
                sleep(1);
               
                $output = explode("\n" ,$this->compute->instances->getSerialPortOutput(
                    $this->project,
                    $this->instance->getZone(),
                    $this->instance->getId(),
                    ['port' => 4])
                        ->getContents()
                );
               
               
                if(isset($output['0']) && strlen($output['0']) > 400){
                    // $str = print_r($output['0'], true);
                    break;
                }
            }

        
       

        $encodedOutput = array_filter($output);
        $encodedOutput = end($encodedOutput);

        return json_decode($encodedOutput);
    }

    private function decryptPassword($decodedOutput, $key): string
    {
        
       
        for($i =0; $i  < 240; $i++){
            sleep(1);
            $encryptedMessage = base64_decode($decodedOutput->encryptedPassword);
            openssl_private_decrypt($encryptedMessage, $decrypted, $key, OPENSSL_PKCS1_OAEP_PADDING);
    
           
            if(isset($decrypted) && $decrypted != null){
                // $str = print_r($output['0'], true);
                break;
            }
        }

        return $decrypted;
    }

    private function getExpirationTimeString(): string
    {
        $date = (new \DateTime())->setTimezone(new \DateTimeZone('UTC'));
        $date->modify('+5 minutes');
        $timestamp = $date->getTimestamp();

        return date('Y-m-d\TH:i:s\Z', $timestamp);
    }
}