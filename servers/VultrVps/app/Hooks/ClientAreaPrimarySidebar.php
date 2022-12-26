<?php
/* * ********************************************************************
*  ProxmoxVPS Product developed. (26.03.19)
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


$hookManager->register(
    function (\WHMCS\View\Menu\Item $primarySidebar)
    {

        /**
         * @var  main\Core\Http\Request $request
         */
        $request = \ModulesGarden\Servers\VultrVps\Core\Helper\sl('request');
        if (!$request->get('id'))
        {
            return;
        }
        $clientAreaSideBar = new \ModulesGarden\Servers\VultrVps\App\Helpers\ClientAreaSidebar($request->get("id"), $primarySidebar);
        if ($request->get('action') != "productdetails"){
            return;
        }
        if(!$clientAreaSideBar->isHostingActiveAndValidServertType()){
            return;
        }
        if($request->get('modop')=='custom'){
            $clientAreaSideBar->informationReplaceUri();
        }
        $clientAreaSideBar->build();

    }, 943
);
