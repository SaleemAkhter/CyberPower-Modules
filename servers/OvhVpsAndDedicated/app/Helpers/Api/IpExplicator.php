<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Api;

/**
 * Class IpExplicator
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class IpExplicator
{
    public static function cidrl($cidr, &$error_code = 0, $callback = null) {
        $error_code = 0;
        sscanf($cidr, "%[^/]/%u", $network, $bits);
        $addr = ip2long($network);
        if ($addr === false) {
            $error_code = 2;
            return false;
        }

        if ($bits == 32) {
            if (is_callable($callback)) {
                $callback(long2ip($addr));
                return array();
            }
            return array(long2ip($addr). "/{$bits}/{$network}");
        }

        if ($bits > 32) {
            $error_code = 3;
            return false;
        }

        $mask = ~(0xFFFFFFFF >> $bits);

        $addr_start = $addr & $mask;
        $addr_end = ($addr & $mask) | ~$mask;

        $addresses = array();
        for ($i = $addr_start; $i <= $addr_end; $i++) {
            if (is_callable($callback)) $callback(long2ip($i));
            else $addresses[] = long2ip($i) . "/{$bits}/{$network}";
        }
        return $addresses;
    }
}