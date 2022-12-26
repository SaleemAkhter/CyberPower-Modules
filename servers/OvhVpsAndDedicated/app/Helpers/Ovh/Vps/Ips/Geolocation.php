<?php

namespace ModulesGarden\Servers\OvhVpsAndDedicated\App\Helpers\Ovh\Vps\Ips;

/**
 * Class Geolocation
 *
 * @author Artur Pilch <artur.pi@modulesgarden.com>
 */
class Geolocation
{
    CONST GEOLOCATIONS = [
        "au",
        "be",
        "ca",
        "cz",
        "de",
        "es",
        "fi",
        "fr",
        "ie",
        "it",
        "lt",
        "nl",
        "pl",
        "pt",
        "sg",
        "uk",
        "us",
    ];

    public static function get($toUpper = true)
    {
        $out = [];

        foreach (SELF::GEOLOCATIONS as $location)
        {
            $out[$location] = $toUpper ? strtoupper($location) : $location;
        }

        return $out;
    }
}