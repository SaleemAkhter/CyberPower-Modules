<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions;

/**
 * Description of PermissionsEncode
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class PermissionsEncode
{
    protected $permissionsNumbers = [
        'read'    => 4,
        'write'   => 2,
        'execute' => 1
    ];

    public function getCode(array $data)
    {

        $owner  = $this->getCodeByType($data, PermissionsGroupsConstants::OWNER);
        $group  = $this->getCodeByType($data, PermissionsGroupsConstants::GROUP);
        $others = $this->getCodeByType($data, PermissionsGroupsConstants::OTHERS);

        return $owner . $group . $others;
    }

    public function getCodeByType(array $data, $type)
    {
        $options = [];
        foreach ($data as $key => $val)
        {
            if (strpos($key, $type) !== false)
            {
                $key                    = str_replace($type, '', $key);
                $options[lcfirst($key)] = $val;
            }
        }

        return $this->calculateCode($options);
    }

    public function calculateCode(array $options)
    {
        $code = 0;
        foreach ($options as $option => $val)
        {
            if ($val == 'on')
            {
                $code += $this->permissionsNumbers[$option];
            }
        }

        return $code;
    }
}
