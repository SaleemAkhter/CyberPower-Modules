    </div>
    <div class="col-lg-6">
        <div class="row hidden-sm-down">
            <div class="col-lg-12 text-right">
                <a  href="{$rawObject->mainUrl()}&a=management&mg-page=MessageSystem" class="lu-btn  lu-btn--success mt-10 mb-10">{$MGLANG->absoluteT('messagesystem')} <span class="badge badge-success">{$rawObject->getMessagesCount()}</span></a>
                        
                    {if $rawObject->isImpersonated()=='reseller'}
                        <a href="{$rawObject->mainUrl()}&a=management&mg-page=UserManager&mg-action=ResellerLevelAccess" class="lu-btn  lu-btn--primary mt-10 mb-10"><span class="lu-btn__text">{$MGLANG->absoluteT('backtoadminlevel')}</span></a>
                    {/if}
                    {if $rawObject->isImpersonated()=='user'}
                        <a href="{$rawObject->mainUrl()}&a=management&mg-page=users&mg-action=ResellerLevelAccess" class="lu-btn  lu-btn--primary mt-10 mb-10"><span class="lu-btn__text">{$MGLANG->absoluteT($rawObject->impersonationLabel())}</span></a>
                    {/if}
                    {if $rawObject->isImpersonated()==''}
                        <a id="hefnc" href="{$rawObject->mainUrl()}&a=management&mg-page=users&mg-action=ResellerLevelAccess" class="lu-btn  lu-btn--primary mt-10 mb-10"><span class="lu-btn__text">{$MGLANG->absoluteT($rawObject->impersonationLabel())}</span></a>
                    {/if}
                

            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="col-sm-6">
                    <h4>{$MGLANG->T('diskSpace')}</h4>
                    <input type="text" value="{$rawObject->getDiskpercent()|substr:0:-1}" class="dial-usage" data-width="100" data-height="100" data-min="0" data-readOnly="true" />
                    <p>{$rawObject->getDiskusage()}MB / {$rawObject->getDisklimit()}MB</p>
                </div>
                <div class="col-sm-6">
                    <h4>{$MGLANG->T('bandwidth')}</h4>
                    <input type="text" value="{$rawObject->getBwpercent()|substr:0:-1}" class="dial-usage" data-width="100" data-height="100" data-min="0" data-readOnly="true" />
                    <p>{$rawObject->getBwusage()}MB / {$rawObject->getBwlimit()}MB</p>
                </div>
            </div>
            <div class="clearfix">
            </div>


    </div>
    <div class="row">
        <div class="col-lg-12">




    </div></div>
</div>
