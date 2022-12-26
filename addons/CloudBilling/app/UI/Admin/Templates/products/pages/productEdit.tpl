<div class="lu-row">
    {foreach from=$customTplVars.groups key=tplKey item=tplValue}
        <div class="lu-col-md-3">
            <div class="lu-row lu-row--eq-height">
                <div class="lu-col-lg-12">
                    <div class="lu-widget">
                        <div class="lu-widget__header">
                            <div class="lu-widget__top lu-top">
                                <div class="lu-top__title">{$tplValue->title}</div>
                            </div>
                        </div>
                        <div class="lu-widget__body" data-check-container>
                            <div style="" class="lu-widget__content">
                                <ul class="lu-list lu-list--info">
                                    {foreach from=$tplValue->services->service key=tplVKey item=tplVValue}
                                        <li class="lu-list__item">
                                            <div class="lu-rail">
                                                <div class="lu-form-check" style="margin-top: 4px; margin-bottom: 4px;">
                                                    <label><input type="checkbox" class="lu-form-checkbox lu-table-mass-action-check" value="12">
                                                        <span class="lu-form-indicator"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="lu-list__item-title" style="flex-basis : 90%; margin-left: 10px; line-height: 50%; margin-top: auto; margin-bottom: auto; ">{$tplVValue->name}</span>
                                            {assign var="chargePercent" value=$rawObject->getRandomCharge()}
                                            <span class="lu-list__value" style="flex-basis : 10%; margin-left: 30px;">
                                                {if $chargePercent}
                                                    <span class="label" style="    font-size: small; padding: 3px; padding-left: 7px; padding-right: 5px; margin-left: 0px; margin-right: 10px; margin-bottom: 0px; color: #505459; background: #e9ebf0;">{$chargePercent} %</span>
                                                {/if}
                                                {$rawObject->insertServiceButton($tplVValue->name)}
                                            </span>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/foreach}
</div>
