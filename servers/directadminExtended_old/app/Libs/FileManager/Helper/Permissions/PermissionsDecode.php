<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\FileManager\Helper\Permissions;

/**
 * Description of PermissionsDecode
 *
 * @author Michal Zarow <michal.za@modulesgarden.com>
 */
class PermissionsDecode
{
    protected $permissionsOptions = [
        7 => ['read', 'write', 'execute'],
        6 => ['read', 'write'],
        5 => ['read', 'execute'],
        4 => ['read'],
        3 => ['write', 'execute'],
        2 => ['write'],
        1 => ['execute']
    ];

    public function getOptions($code)
    {
        $exploded   = str_split($code);
        $ownerCode  = $exploded[0];
        $groupCode  = $exploded[1];
        $othersCode = $exploded[2];

        $ownerOptions  = $this->getOptionsByGroup(PermissionsGroupsConstants::OWNER, $ownerCode);
        $groupOptions  = $this->getOptionsByGroup(PermissionsGroupsConstants::GROUP, $groupCode);
        $othersOptions = $this->getOptionsByGroup(PermissionsGroupsConstants::OTHERS, $othersCode);

        return array_merge($ownerOptions, $groupOptions, $othersOptions);
    }

    public function getOptionsByGroup($type, $code)
    {
        $options = [];
        $opts    = $this->permissionsOptions[$code];
        foreach ($opts as $opt)
        {
            $options[$type . ucfirst($opt)] = 'on';
        }

        return $options;
    }
}
