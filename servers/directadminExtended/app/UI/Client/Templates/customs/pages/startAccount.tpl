<div class="row">
    <div class="{$rawObject->getClass()}">
        <div class="row hidden-sm-up ">
            <div class="col-lg-12 text-right">
                <a  href="{$rawObject->mainUrl()}&a=management&mg-page=MessageSystem" class="lu-btn lu-btn--xs lu-btn--success mt-10 mb-10">{$MGLANG->absoluteT('messagesystem')} <span class="badge badge-success">{$rawObject->getMessagesCount()}</span></a>
                {if $rawObject->isImpersonated()}
                    <a href="{$rawObject->mainUrl()}&a=management&mg-page=users&mg-action=ResellerLevelAccess" class="lu-btn lu-btn--xs lu-btn--primary mt-10 mb-10"><span class="lu-btn__text">{$MGLANG->absoluteT('backtoresellerlevel')}</span></a>
                {/if}

            </div>
        </div>
