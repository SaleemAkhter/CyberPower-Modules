{**********************************************************************
* ModuleFramework product developed. (2017-08-24)
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

{**
* @author Sławomir Miśkowicz <slawomir@modulesgarden.com>
*}

<div class="vhidden" id="mgVueMainContainer">
    <div class="lu-row"><i v-show="pageLoading" class="page_processing"></i></div>
    {foreach from=$elements key=nameElement item=dataElement}
        {$dataElement->getHtml()}
    {/foreach}
    {literal}
    
    <mg-modal v-if="showModal" @close="showModal = false" :bodydata="htmlContent"></mg-modal>
    
    <div id="vueModalContainer"></div>
</div>
        
<script type="text/x-template" id="mg-modal-wrapper">
    <div v-html="bodydata" ></div>
</script>
{/literal}

<script type="text/javascript">
    function mgLoadPageContoler(){
        mgPageControler = new mgVuePageControler('mgVueMainContainer');
        mgPageControler.vinit();
    };
    
    document.onreadystatechange = function () {
        if (document.readyState === "interactive") {
            mgLoadPageContoler();
        }
    };
</script>

{if $scriptHtml}
    <script type="text/javascript">
        {$scriptHtml}
    </script>
{/if}
