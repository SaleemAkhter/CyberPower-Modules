<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Nov 29, 2017)
 * *
 *
 *  CREATED BY MODULESGARDEN       ->       http://modulesgarden.com
 *  CONTACT                        ->       contact@modulesgarden.com
 *
 *
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.
 *
 *
 * ******************************************************************** */

namespace ModulesGarden\WordpressManager\App\Interfaces;

use ModulesGarden\WordpressManager\App\Models\Installation;
use ModulesGarden\WordpressManager\App\Modules\Cpanel\Wp;

/**
 * Description of WordPressModuleInterface
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
interface WordPressModuleInterface
{

    public function setParams(array $params);

    /**
     * Get WordPress Installations
     * @return array  wp Installations
     */
    public function getInstallations($appIds=[]);

    public function installationCreate(array $data);

    public function installationDetail($id);

    public function installationDelete($installationId, array $data);

    public function installationUpdate($installationId, array $data);
    
    public function getBackups($installationId);

    /**
     * 
     * @param array $data['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data['backupDirectory'] 
     * @param array $data['backupDatabase']
     * @param array $data['note']
     * @return array
     */
    public function backupCreate(array $data);

    public function backupDownload($backupId, Installation $installation);

    public function backupRestore($backupId, Installation $installation);

    public function backupDelete($backupId, Installation $installation);

    /**
     * 
     * @param array $data['installationId'] - The Installation ID that you want to clone. It can be fetched from List Installed Script
     * @param array $data['domain'] - This is the domain on which you wish to clone the installation
     * @param array $data['directory'] - This is the sub-directory to clone the installation in. Leave it blank to clone in root of the domain
     * @param array $data['db'] - This is the database name for the cloned installation. If the script does not require database you can leave this blank.
     * @return array
     */
    public function installationClone(array $data);

    public function installationUpgrade($installationId);

    /**
     * 
     * @param string $installationId
     * @return string SSO URL
     */
    public function getSingleSignOnUrl($installationId);

    public function cacheFlush(Installation $installation);

    public function getPlugins(Installation $installation);

    public function pluginActivate(Installation $installation, $pluginName);

    public function pluginInstall(Installation $installation, $pluginName);
       
    public function pluginDeactivate(Installation $installation, $pluginName);

    public function pluginUpdate(Installation $installation, $pluginName);

    public function getThemes(Installation $installation);

    /**
     * 
     * @param Installation $installation
     * @return WordPressConfigInterface
     */
    public function getConfig(Installation $installation);

    public function domainCreate(array $data);

    public function domainInfo();

    public function getAddonDomains();

    public function getChangeDomainFields(Installation $installation);

    public function setDebugMode($debugMode);
    
    public function getInstallationScript();
    
    public function getInstallationScripts();
    
    public function pluginSearch(Installation $installation, $name);
    /**
     * 
     * @param Installation $installation
     * @return WordPressPluginInterface Description
     */
    public function getPlugin(Installation $installation);

    public function getParams();

    /**
     * 
     * @param Installation $installation
     * @return ThemeInterface Description
     */
    public function getTheme(Installation $installation);

    /**
     * @param Installation $installation
     * @return Wp
     */
    public function getWpCli(Installation $installation);
    
    /**
     * 
     * @param Installation $installation
     * @return InstallationInterface
     */
    public function installation(Installation $installation);
    
    public function import(array $post);
    
    /**
     * @return \ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Ssl 
     */
    public function ssl();
    
    /**
     * @return \ModulesGarden\WordpressManager\App\Modules\Cpanel\Domain|\ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Domain
     */
    public function domain();
    
    public function isSupportChangeDomain();
    /**
     * @return \ModulesGarden\WordpressManager\App\Modules\Cpanel\Reseller|\ModulesGarden\WordpressManager\App\Modules\DirectAdmin\Reseller
     */
    public function reseller();
    
    public function setUsername($username);
    
    public function getUsername();
    
}
