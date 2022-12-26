<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 5, 2018)
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

use \ModulesGarden\WordpressManager\App\Models\Installation as Model;
use \ModulesGarden\WordpressManager\App\Interfaces\InstallationInterface;
/**
 * Description of Installation
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Installation implements InstallationInterface
{
    /**
     *
     * @var Installation
     */
    private $model;
    private $softaCulous;
    
    public function __construct(Model $model)
    {
        $this->model = $model; ;
    }
    /**
     * 
     * @return Softaculous
     */
    public function softaCulous()
    {
        return $this->softaCulous;
    }

    public function setSoftaCulous(Softaculous $softaCulous)
    {
        $this->softaCulous = $softaCulous; 
        return $this;
    }

    public function pushToLive(array $post){

        return $this->softaCulous()->setGet(['act' => 'pushtolive', 'insid' => $this->model->relation_id, 'api'      => 'json'])
                                   ->setPost($post)
                                  ->sendRequest();
    }

    public function staging(array $post)
    {

        $post['softsubmit']=1;
        return $this->softaCulous()->setGet(['act' => 'staging', 'insid' => $this->model->relation_id, 'api'      => 'json'])
                                   ->setPost($post)
                                   ->sendRequest();
    }
    
    public function read(){
        return $this->softaCulous()->setGet(['act' => 'editdetail', 'insid' => $this->model->relation_id, 'api'      => 'json'])
                                   ->setPost([])
                                  ->sendRequest();
    }
    
    public function update(array $post){
        $post['editins']='1';
        return $this->softaCulous()->setGet(['act' => 'editdetail', 'insid' => $this->model->relation_id, 'api'      => 'json'])
                                   ->setPost($post)
                                  ->sendRequest();
    }

    public function upgrade(array $post)
    {
        return $this->softaCulous()->setGet(['act' => 'upgrade', 'insid' => $this->model->relation_id, 'api'      => 'json'])
                                   ->setPost($post)
                                   ->sendRequest();
    }

    public function deleting(array $post)
    {
        return $this->softaCulous()->setGet(['act' => 'remove', 'insid' => $this->model->relation_id, 'api'      => 'json'])
            ->setPost($post)
            ->sendRequest();
    }
}
