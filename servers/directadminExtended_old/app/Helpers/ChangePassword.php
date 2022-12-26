<?php


namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

use ModulesGarden\DirectAdminExtended\Core\UI\Traits\RequestObjectHandler;
use ModulesGarden\DirectAdminExtended\Core\Models\Whmcs\Hosting;

class ChangePassword
{
    use RequestObjectHandler;

    public function encryptWhmcsPassword($newPassword)
    {
        $command = 'EncryptPassword';
        $postData = [
            'password2' => $newPassword,
        ];

        $results = localAPI($command, $postData, '');

        if($results['result'] == 'success')
        {
            return $results['password'];
        }
        return false;
    }

    public function changeWhmcsPassword($newPassword)
    {
        $hostingId = $this->getRequestValue('id');
        $newPassword = $this->encryptWhmcsPassword($newPassword);

        if($newPassword && $hostingId)
        {
            $model = new Hosting();

            $model->where('id', '=', $hostingId)
                ->update(['password' => $newPassword]);

            return true;
        }
        return false;
    }
}