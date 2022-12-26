<?php

/* * ********************************************************************
 * Wordpress_Manager Product developed. (Dec 20, 2017)
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

namespace ModulesGarden\WordpressManager\App\Modules\Softaculous;

use ModulesGarden\WordpressManager\App\Interfaces\SoftaculousApiProvider;
use \ModulesGarden\WordpressManager\App\Helper\UtilityHelper;
use \ModulesGarden\WordpressManager\Core\Lang;
use \ModulesGarden\WordpressManager\App\Helper\LangException;
/**
 * Description of Api
 *
 * @author Pawel Kopec <pawelk@modulesgarden.com>
 */
class Softaculous
{
    private $cache = [];

    /**
     *
     * @var \ModulesGarden\WordpressManager\App\Interfaces\SoftaculousApiProvider|PleskProvider
     */
    private $provider;

    function __construct($provider)
    {
        $this->provider = $provider;
    }

    function setProvider($provider)
    {
        $this->provider = $provider;
    }

    function getProvider()
    {
        return $this->provider;
    }

    public function getInstallations($showupdates = false)
    {

        $this->provider->setGet([
            'act'         => 'installations',
            'showupdates' => $showupdates,
            'api'         => 'json'
        ]);
        $this->provider->setPost([]);
        return $this->provider->sendRequest();
    }

    public function getInstallationScripts()
    {
        $this->provider->setGet([
            'act' => 'home',
            'api' => 'json'
        ]);
        $this->provider->setPost([]);
        $response                = $this->provider->sendRequest();
        $this->cache['iscripts'] = $response['iscripts'];
        return $response;
    }
    
    /**
     * 
     * @return array
     * @throws \Exception
     */
    public function getWordPressInstallationScript()
    {
        if (!$this->cache['iscripts'])
        {
            $this->getInstallationScripts();
        }
        foreach ($this->cache['iscripts'] as $id => $v)
        {
            if ($v['softname'] == "wp")
            {
                $v['id']=$id;
                return $v;
            }
        }
        throw (new LangException("WordPress Installation script not found"))->translate();
    }
    
    public function getWordPressInstallationScripts()
    {
        if (!$this->cache['iscripts'])
        {
            $this->getInstallationScripts(); 
        }
        $data=[];
        foreach ($this->cache['iscripts'] as $id => $v)
        {
            $v['title'] = "#{$id} ".$v['name']; 
            $v['id'] = $id;
            $data[$id]=(array) $v;
            
            
        }
        if(!$data){
            throw (new LangException("WordPress Installation scripts not found"))->translate();
        }
        return $data;
    }

    public function installationCreate($scriptId, $post = [])
    {
        if (!$this->cache['iscripts'])
        {
            $this->getInstallationScripts();
        }
        if (!$this->cache['iscripts'][$scriptId])
        {
            throw new \Exception(sprintf('Script \'%s\' Not Found', $scriptId));
        }
        // Is JS / PERL or PHP
        if ($this->cache['iscripts'][$scriptId]['type'] == 'js')
        {
            $this->provider->setGet([
                'act'  => 'js',
                'api'  => 'json',
                'soft' => $scriptId,
            ]);
        }
        elseif ($this->cache['iscripts'][$scriptId]['type'] == 'perl')
        {
            $this->provider->setGet([
                'act'  => 'perl',
                'api'  => 'json',
                'soft' => $scriptId,
            ]);
        }
        else
        {
            $this->provider->setGet([
                'act'  => 'software',
                'api'  => 'json',
                'soft' => $scriptId,
            ]);
        }
        if (!empty($post))
        {
            $post['softsubmit'] = 1;
        }
        $this->provider->setPost($post);
        return $this->provider->sendRequest();
    }

    public function installationDelete($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'remove',
            'api'   => 'json',
            'insid' => $installationId,
        ]);
        $this->provider->setPost(array_merge(['removeins' => 1],$post));
        return $this->provider->sendRequest();
    }

    public function installationClone($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'sclone',
            'api'   => 'json',
            'insid' => $installationId,
        ]);
        $this->provider->setPost($post);
        return $this->provider->sendRequest();
    }

    public function installationUpgrade($installationId)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'upgrade',
            'api'   => 'json',
            'insid' => $installationId,
        ]);
        $this->provider->setPost(['softsubmit' => 1]);
        return $this->provider->sendRequest();
    }

    public function autodetectWp($post)
    {

        // act=sync&jsnohf=1&import_all=1
        $this->provider->setGet([
            'act'   => 'sync',
            'import_all' => 1,
            'api'         => 'json',
            'jsnohf'=>1,
            'json'=>'yes'
        ]);
        $this->provider->setPost($post);
        $response= $this->provider->sendRequest();
        return $response;
    }
    public function installationUpdateSiteDetails($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'wordpress',
            'insid' => $installationId,
            'api'         => 'json',
            'json'=>'yes'
        ]);
        $post['editins']=1;

        $this->provider->setPost($post);
        $response= $this->provider->sendRequest();
        return $response;
    }
    public function installationUpdateOld($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'editdetail',
            'insid' => $installationId,
            'api'         => 'json'
        ]);
        $post['editins']=1;
        $this->provider->setPost($post);
        return $this->provider->sendRequest();
    }
    public function installationUpdate($installationId, $post)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        // die("Disable edit api as causing issue ");
        $this->provider->setGet([
            'act'   => 'editdetail',
            'insid' => $installationId,
            'api'   => 'json'
        ]);
        $post['editins']=1;

        $this->provider->setPost($post);
        $response= $this->provider->sendRequest();
        return $response;
    }
    
    public function installationDetail($installationId)
    {
        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'editdetail',
            'api'   => 'json',
            'insid' => $installationId,
        ]);
        $this->provider->setPost([]);
        return $this->provider->sendRequest();
    }

    public function getBackups()
    {
        $this->provider->setGet([
            'act' => 'backups',
            'api' => 'json',
        ]);
        $this->provider->setPost([]);
        $resp = $this->provider->sendRequest();
        return $resp['backups'];
    }

    public function backupCreate($installationId, $post)
    {

        if (!$installationId)
        {
            throw new \Exception("Installation ID cannot be empty");
        }
        $this->provider->setGet([
            'act'   => 'backup',
            'api'   => 'json',
            'insid' => $installationId,
        ]);
        $post['backupins'] = 1;
        $this->provider->setPost($post);
        return $this->provider->sendRequest();
    }

    public function signOn($installationId)
    {
        
        $this->provider->setGet([
            'act'   => 'sign_on',
            'api'   => 'json',
            'insid' => $installationId,
            'autoid' => UtilityHelper::generatePassword(32,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' )
        ]);
        $this->provider->setPost([]);
        return $this->provider->sendRequest();
    }

    public function backupDelete($fileName)
    {
        $this->provider->setGet([
            'act'    => 'backups',
            'api'    => 'json',
            'remove' => $fileName,
        ]);
        $this->provider->setPost(['in_sid' => $fileName]);
        return $this->provider->sendRequest();
    }

    public function backupRestore($fileName, $post = [])
    {
        $this->provider->setGet([
            'act'     => 'restore',
            'api'     => 'json',
            'restore' => $fileName,
        ]);
        $post['restore_dir']     = 1;
        $post['restore_db']      = 1;
        $post['restore_datadir'] = 1;
        $post['restore_wwwdir']  = 1;
        $post['restore_ins']     = 1;
        $this->provider->setPost($post);
        return $this->provider->sendRequest();
    }

    public function backupDownload($fileName)
    {
        $this->provider->setGet([
            'act'      => 'backups',
            'api'      => 'json',
            'download' => $fileName,
        ]);
        $this->provider->setPost([]);
        $this->provider->sendRequest();
        return $this->provider->getResponse();
    }
    public function pluginUpload($requestdata)
    {
        $this->provider->setGet([
            'act'=> 'wordpress',
            'upload'=> 1,
            'api'=> 'json',
        ]);
        $this->provider->setPost($requestdata);
        $this->provider->sendRequest();
        return $this->provider->getResponse();
    }
    public function setGet(array $get){
        $this->provider->setGet($get);
        return $this;
    }
    
    public function setPost(array $post){
        $this->provider->setPost($post);
        return $this;
    }
    
    public function sendRequest(){
        return $this->provider->sendRequest();
    }



}
