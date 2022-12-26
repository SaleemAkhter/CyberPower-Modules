{* <div class="lu-widget {$rawObject->getid()}_page_container" >
    <div class="lu-widget__body">
        <div class="lu-row">
            <div class="lu-col-md-12">
                <div class="box light ">
                    <div class="box-title pl-20 pr-20 pt-10 " style="background-color:#c7e9f4;border-bottom: 1px solid #edeff2;">
                        <div class="caption">
                            <span class="caption-subject bold font-red-thunderbird uppercase">
                                <h6><strong>   </strong></h6>
                            </span>
                        </div>
                    </div>
                    <div class="content pl-40 pr-40 pb-20 pt-20">

                    </div>
                    <div class="footer pl-40 pr-40 pt-20 pb-10" style="background-color:#c7e9f4;">
                        <div class="row">
                            <div class="col-lg-6 text-left">
                                <button type="button" class="lu-btn lu-btn--success mg-submit-form">Clear</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> *}

<div class="lu-widget {$rawObject->getid()}_page_container" >
    <div class="lu-widget__body">
        <div class="lu-row">
            <div class="{if $rawObject->getClasses()}{$rawObject->getClasses()}{else} lu-col-md-12 {/if}">
                <div class="box light">
                    <div class="box-title form-title pl-20 pr-20 pt-10 " >
                        <div class="caption">
                            {if $rawObject->getIcon()}<i class="{$rawObject->getIcon()}"></i>{/if}
                            <span class="caption-subject bold font-red-thunderbird uppercase">
                                {if $rawObject->isRawTitle()}{$rawObject->getRawTitle()}{elseif $rawObject->getTitle()}{$MGLANG->T($rawObject->getTitle())}{/if}
                            </span>
                        </div>
                        <div class="rc-actions lu-pull-right" style="display: inline-flex;"></div>
                    </div>
                    <div class="box-body mt-10 ml-20 mr-20 mb-10">
                        <div class="lu-row">
                            <div class="lu-col-md-12">
                                <form id="{$rawObject->getId()}" mgformtype="{$rawObject->getFormType()}" id="{$elementId}">
                                    {foreach from=$rawObject->getSections() item=section}
                                    {$section->getHtml()}
                                    {/foreach}


                                    {foreach from=$rawObject->getFields() item=field }
                                    {$field->getHtml()}
                                    {/foreach}

                                    <div class="lu-col-md-12 ui-form-submit">
                                        {$rawObject->getSubmitHtml()}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
