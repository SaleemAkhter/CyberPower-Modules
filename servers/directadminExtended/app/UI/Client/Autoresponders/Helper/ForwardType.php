<?php

namespace ModulesGarden\Servers\DirectAdminExtended\App\UI\Client\Autoresponders\Helper;

class ForwardType
{
    const PERMANENTLY = 'std_fwd';
    const TEMPORARILY = 'std_fwd';
    const FRAME       = 'frm_fwd';

    public static function getByCode($type)
    {
        if ($type === self::FRAME)
        {
            return self::FRAME;
        }
        if ((int)$type === 301)
        {
            return self::PERMANENTLY;
        }
        if ((int)$type === 302)
        {
            return self::TEMPORARILY;
        }
    }
}
