<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator;

use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Interfaces\AbstractSubmodule;
use ModulesGarden\Servers\DirectAdminExtended\App\Libs\PasswordGenerator\Submodules\DirectAdmin;

/**
 *
 * Created by PhpStorm.
 * User: Tomasz Bielecki ( tomasz.bi@modulesgarden.com )
 * Date: 25.09.19
 * Time: 13:53
 * Class PaswordGenerator
 */
class Generator
{
    const ADDITIONAL_LENGTH = 2;

    /**
     * @param AbstractSubmodule $submodule
     * @return string
     */
    public static function generate(AbstractSubmodule $submodule)
    {
        /**
         * set password length
         */
        $length = $submodule->getMinLength() + Generator::ADDITIONAL_LENGTH;
        $length = $length > $submodule->getMaxLength() ? $submodule->getMinLength() : $length;

        /**
         * array for all required chars
         */
        $merged = [];

        /**
         * check if numbres is required
         */
        if ($submodule->isRequiredNumbers())
        {
            $key    = array_rand(CharsMap::NUMBERS);
            $tmp[]  = CharsMap::NUMBERS[$key];
            $merged = array_merge(CharsMap::NUMBERS, $merged);
        }

        /**
         * check if chars is required
         */
        if ($submodule->isRequiredChars())
        {
            $key    = array_rand(CharsMap::SPECIAL);
            $tmp[]  = CharsMap::SPECIAL[$key];
            $merged = array_merge(CharsMap::SPECIAL, $merged);
        }

        /**
         * check if special is required
         */
        if ($submodule->isRequiredSpecial())
        {
            $key    = array_rand(CharsMap::CHARS);
            $tmp[]  = CharsMap::CHARS[$key];
            $merged = array_merge(CharsMap::CHARS, $merged);
        }

        $merged = array_merge(CharsMap::CHARS, CharsMap::NUMBERS, CharsMap::SPECIAL);

        /**
         * generate password
         */
        do
        {
            $key   = array_rand($merged);
            $tmp[] = $merged[$key];
        } while (count($tmp) < $length);

        /**
         * return decoded password
         */
        return html_entity_decode(implode($tmp), ENT_QUOTES);
    }

}