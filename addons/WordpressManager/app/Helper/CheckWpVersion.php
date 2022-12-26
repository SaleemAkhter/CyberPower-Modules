<?php

namespace ModulesGarden\WordpressManager\App\Helper;

use \ModulesGarden\WordpressManager\App\Models\ModuleSettings;

class CheckWpVersion
{
    public function checkIfNewer($recentVersion){
        /* Checking if there is new version of Wordpress by their API */
        $this->checkWordpressApi();

        /* Version compare */
        if(version_compare($recentVersion, (new ModuleSettings())->getWordpressVersion(), '>='))
        {
            $result = false;
        } else
        {
            $result = true;
        }

        return $result;
    }

    public function checkWordpressApi()
    {
        $moduleSettings = new ModuleSettings();
        $result = $this->curl('https://api.wordpress.org/core/version-check/1.7/');
        $wordpressApiNewestVersion = json_decode($result,true)['offers'][0]['version'];

        if($moduleSettings->getWordpressVersion() != $wordpressApiNewestVersion)
        {
            $moduleSettings->setSettings(['wordpressVersion' => $wordpressApiNewestVersion]);
        }
    }

    private function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
