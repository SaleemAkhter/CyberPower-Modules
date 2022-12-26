<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Oct 30, 2018)
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

namespace ModulesGarden\WordpressManager\App\Cron;

use ModulesGarden\WordpressManager\App\Events\InstallationCreatedEvent;
use \ModulesGarden\WordpressManager\Core\CommandLine\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Style\SymfonyStyle;
use \ModulesGarden\WordpressManager as main;
use ModulesGarden\WordpressManager\App\Models\Installation;
use \ModulesGarden\WordpressManager\App\Models\Whmcs\Hosting;

/**
 * Description of Synchronize
 *
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
class Synchronize extends Command
{

    use main\App\Http\Admin\BaseAdminController;
    /**
     * Command name
     * @var string
     */
    protected $name        = 'Synchronize';

    /**
     * Command description
     * @var string
     */
    protected $description = '';

    /**
     * Command help text
     * @var string
     */
    protected $help        = '';


    protected function process(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {

        $io->title('Synchronize tasks: Starting');
        $i = 0;
        //Get products
        foreach ($this->getProductsEnabled() as $product)
        {
            //Get hosting
            $query = Hosting::where('packageid', $product->id)
                    ->select("id")
                    ->where('domainstatus', 'Active');

            foreach ($query->get() as $hosting)
            {

                $output->writeln(sprintf("Synchronize Hosting: %s", $hosting->id));
                $this->reset();
                try
                {
                    $this->setHostingId($hosting->id);
                    //Is reseller account
                    if ($this->getHosting()->product->isTypeReseller())
                    {
                        $this->resellerProcess($input,  $output,  $io);
                    }elseif($this->getHosting()->product->isTypeAdmin()){
                         $this->adminProcess($input,  $output,  $io);
                    }
                    else
                    {
                        $this->hostingProcess();
                    }
                    $i++;
                    $output->writeln(sprintf("Hosting: %s has been synchronized", $hosting->id));
                }
                catch (\Exception $ex)
                {
                    $io->error($ex->getMessage());
                }
            }
            unset($product, $hosting);
        }
        //Delete terminated services
        $this->clean();
        $output->writeln("");
        $io->success([
            sprintf("Synchronize tasks: %s Entries Processed.", $i),
            "Synchronize tasks: Done"
        ]);
        //Queue
        $command = $this->getApplication()->find('queue');
        $command->run($input, $output, $io);

        //PluginBlocked
        $command = $this->getApplication()->find('pluginblocked');
        $command->run($input, $output, $io);

        //ThemeBlocked
        $command = $this->getApplication()->find('themeblocked');
        $command->run($input, $output, $io);

        //WpUpdateMailNotification
        $command = $this->getApplication()->find('updateWpMail');
        $command->run($input, $output, $io);
        
    }

    private function hostingProcess()
    {
        $relationIds = [];
        $appIds      = array_keys($this->getHosting()->productSettings->getSettings()['installationScripts']);

        foreach ($this->subModule()->getInstallations($appIds) as $installation)
        {


            //Live installation does not exist
            if($installation['staging'] && !Installation::where('hosting_id', $this->getHosting()->id)->where('relation_id', $installation['staging'])->count()){
                continue;
            }
            //Create New or Update
            $repository = new main\App\Repositories\InstallationRepository();
            $model      = $repository->forHostingAndRelation($this->getHosting(), $installation['id']);
            //for plesk
            if(!$model->id && Installation::ofRelationId($model ->relation_id)->ofUserId(  $model->user_id)->count() ){
                continue;
            }
            $model->site_name = $installation['site_name'];
            $model->additional_data = $installation;
            if(!$model->id &&  $installation['domain_id']){
                $model->domain_id = $installation['domain_id'];
            }
            if (!$model->domain && !$model->url && !$model->path)
            {
                $model->domain = !empty($installation['domain']) ? $installation['domain'] : parse_url($installation['url'], PHP_URL_HOST);
                $model->url    = $installation['url'];
                $model->path   = $installation['path'];
            }
            if($model->url && $model->path && ($model->url != $installation['url'] || $model->path != $installation['path'] )){
                $model->url    = $installation['url'];
                $model->path   = $installation['path'];
            }
            $model->staging = $installation['staging'];
            $model->version = $installation['version'];
            $isNew =  !$model->id;

            $model->save();
            if($isNew){
                main\Core\Helper\fire(new InstallationCreatedEvent($model));
            }
            //Related Id
            $relationIds[]  = $installation['id'];
        }
        //Delete
        Installation::ofHostingId($this->getHosting()->id)
                ->ofRelationNotIn($relationIds)
                ->delete();
    }
     private function adminProcess(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $relationIds = [];
        $appIds      = (array) $this->getHosting()->productSettings['installationScripts'];
        if(empty($appIds)){
            $appIds=[26];
        }
        $accounts=$this->subModule()->admin()->getAccounts();


        foreach ($accounts as $data)
        {
            try{
                $this->subModule()->setUsername($data['username']);
                foreach ($this->subModule()->getInstallations($appIds) as $installation)
                {
                    //Live installation does not exist
                    if($installation['staging'] && !Installation::where('hosting_id', $this->getHosting()->id)->where('relation_id', $installation['staging'])->count()){
                        continue;
                    }
                    //Create New or Update
                    $repository = new main\App\Repositories\InstallationRepository();
                    $model      = $repository->forHostingAndRelation($this->getHosting(), $installation['id']);
                    if (!$model->domain && !$model->url && !$model->path)
                    {
                        $model->domain = !empty($installation['domain']) ? $installation['domain'] : parse_url($installation['url'], PHP_URL_HOST);
                        $model->url    = $installation['url'];
                        $model->path   = $installation['path'];
                    }
                    if($model->url && $model->path && ($model->url != $installation['url'] || $model->path != $installation['path'] )){
                        $model->url    = $installation['url'];
                        $model->path   = $installation['path'];
                    }
                    $model->staging = $installation['staging'];
                    $model->version = $installation['version'];
                    $model->username = $data['username'];
                    $model->additional_data = $installation;
                    $model->site_name = $installation['site_name'];
                    $isNew =  !$model->id;
                    $model->save();
                    if( $isNew ){
                        main\Core\Helper\fire(new InstallationCreatedEvent($model));
                    }
                    //Related Id
                    $relationIds[]  = $installation['id'];
                }
            }catch (\Exception $ex){
                $io->error($ex->getMessage());
            }
        }
        //Delete
        Installation::ofHostingId($this->getHosting()->id)
                ->ofRelationNotIn($relationIds)
                ->delete();
    }
    private function resellerProcess(InputInterface $input, OutputInterface $output, SymfonyStyle $io)
    {
        $relationIds = [];
        $appIds      = (array) $this->getHosting()->productSettings['installationScripts'];
        if(empty($appIds)){
            $appIds=[26];
        }
        $accounts=$this->subModule()->reseller()->getAccounts();


        foreach ($accounts as $data)
        {
            try{
                $this->subModule()->setUsername($data['username']);
                foreach ($this->subModule()->getInstallations($appIds) as $installation)
                {

                    //Live installation does not exist
                    if($installation['staging'] && !Installation::where('hosting_id', $this->getHosting()->id)->where('relation_id', $installation['staging'])->count()){
                        continue;
                    }
                    //Create New or Update
                    $repository = new main\App\Repositories\InstallationRepository();
                    $model      = $repository->forHostingAndRelation($this->getHosting(), $installation['id']);
                    if (!$model->domain && !$model->url && !$model->path)
                    {
                        $model->domain = !empty($installation['domain']) ? $installation['domain'] : parse_url($installation['url'], PHP_URL_HOST);
                        $model->url    = $installation['url'];
                        $model->path   = $installation['path'];
                    }
                    if($model->url && $model->path && ($model->url != $installation['url'] || $model->path != $installation['path'] )){
                        $model->url    = $installation['url'];
                        $model->path   = $installation['path'];
                    }
                    $model->staging = $installation['staging'];
                    $model->version = $installation['version'];
                    $model->username = $data['username'];
                    $model->additional_data = $installation;
                    $model->site_name = $installation['site_name'];
                    $isNew =  !$model->id;
                    $model->save();
                    if( $isNew ){
                        main\Core\Helper\fire(new InstallationCreatedEvent($model));
                    }
                    //Related Id
                    $relationIds[]  = $installation['id'];
                }
            }catch (\Exception $ex){
                $io->error($ex->getMessage());
            }
        }
        //Delete
        Installation::ofHostingId($this->getHosting()->id)
                ->ofRelationNotIn($relationIds)
                ->delete();
    }

    /**
     * 
     * @return main\Core\Models\Whmcs\Product[]
     */
    public function getProductsEnabled()
    {
        return main\Core\Models\Whmcs\Product::whereIn('id', function($query)
                {
                    $query->select('product_id')
                            ->from(with(new main\App\Models\ProductSetting)->getTable())
                            ->where('enable', '1');
                })->get();
    }

    private function clean()
    {
        Installation::whereNotIn('hosting_id', function($query)
        {
            $query->select('id')
                    ->from((new Hosting())->getTable())
                    ->whereIn("domainstatus", ["Active", "Suspended"]);
        })->delete();
    }
}
