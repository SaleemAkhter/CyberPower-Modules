<?php


namespace ModulesGarden\Servers\GoogleCloudVirtualMachines\App\Helpers;


class Utility
{
    public static function generatePassword($length = 8, $chars = "")
    {
        if (!$chars)
        {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        $count = mb_strlen($chars);
        for ($i = 0, $result = ''; $i < $length; $i++)
        {
            $index  = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }
        return $result;
    }

}