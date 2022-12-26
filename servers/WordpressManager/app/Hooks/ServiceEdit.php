<?php

/* * ********************************************************************
 * WordPress Manager product developed. (Nov 5, 2018)
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

use \ModulesGarden\WordpressManager\App\Models\Installation;
use function \ModulesGarden\WordpressManager\Core\Helper\sl;
/**
 *  ServiceEdit
 * @author Pawel Kopec <pawelk@modulesgardne.com>
 */
$scriptName = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], DIRECTORY_SEPARATOR) + 1);

if ( $scriptName == 'clientsmove.php' && isset( $_REQUEST['type']) && $_REQUEST['type']=='hosting' && $_REQUEST['id'] && isset( $_REQUEST['id']) && isset( $_REQUEST['newuserid']) && $_REQUEST['newuserid'])
{
    check_token();
    $request = sl('request');
    $serviceId = $request->get('id');
    $userId = $request->get('newuserid');
    if(!Installation::where('hosting_id',$serviceId)->count()){
        return;
    }
    Installation::where('hosting_id',$serviceId)->update(['user_id' => $userId]);
}

