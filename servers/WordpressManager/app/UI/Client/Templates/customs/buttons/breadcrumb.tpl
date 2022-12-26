{**********************************************************************
* ModuleFramework product developed. (2017-09-05)
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
**********************************************************************}

<ul class="lu-breadcrumb lu-breadcrumb--angle-separator mb-20" style="padding: 0px 5px 5px 5px">
    <li class="lu-breadcrumb__item ">
            <a class="lu-breadcrumb__link" href="clientarea.php?action=productdetails&id={$smarty.get.id}">{$MGLANG->absoluteT('breadcrumb-dashboard')}</a>
    </li>
    <li class="lu-breadcrumb__item ">
        {if $rawObject->getCurrentAction() =="" || $rawObject->getCurrentAction()=="index"}
            {$MGLANG->absoluteT('breadcrumb-installation-list')}
        {else}
            <a class="lu-breadcrumb__link" href="index.php?m=WordpressManager&id={$smarty.get.id}">{$MGLANG->absoluteT('breadcrumb-installation-list')}</a>
        {/if}

    </li>
    {foreach from=$rawObject->getPages() item=page}
    {if $rawObject->getCurrentAction() eq $page}
        <li class="lu-breadcrumb__item {if $rawObject->getCurrentAction() eq $page || ($rawObject->getCurrentAction() =="" && $rawObject->getPages()|@count ==1)}is-active{/if}">
            <a class="lu-breadcrumb__link" {if $rawObject->getCurrentAction() eq $page || ($rawObject->getCurrentAction() =="" && $rawObject->getPages()|@count ==1)}href="javascript:;"{else}href="{$rawObject->getUrl($page)}"{/if}>{$MGLANG->T($page, $rawObject->getTitle())}</a>
        </li>
        {/if}
    {/foreach}
</ul>
