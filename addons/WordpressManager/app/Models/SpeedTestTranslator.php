<?php

namespace ModulesGarden\WordpressManager\App\Models;

use ModulesGarden\WordpressManager\Core\Lang\Lang;

class SpeedTestTranslator
{
    public static function T(array $data)
    {
        $out = [];
        foreach ($data['diagnostics']['details']['items'][0] as $key => $value) {
            $out[(new Lang())->T($key)] = $value;
        }
        $data['diagnostics']['details']['items'][0] = $out;

        return $data;
    }
}