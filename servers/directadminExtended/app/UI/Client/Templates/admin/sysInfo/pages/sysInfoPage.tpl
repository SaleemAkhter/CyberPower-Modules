<div class="lu-widget">
    <div class="lu-widget__body p-20">
        <div class="lu-tab-content">
            <div class="dataTables_wrapper no-footer">
                <div>
                    <table width="100%" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
                        <tbody>
                            <tr><th>{$MGLANG->T('uptime')}</th><td>{$rawObject->info->uptime_info->uptime}</td></tr>
                            <tr><th>{$MGLANG->T('cpucount')}</th><td>{$rawObject->info->numcpus}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lu-widget">
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">{$MGLANG->T("cpuinformation")}</div>
        </div>
    </div>
    <div class="lu-widget__body p-20">
        <div class="lu-tab-content pt-20">
            <div class="dataTables_wrapper no-footer">
                <div>
                    <table width="100%" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
                        <thead>
                            <tr role="row">
                                <th>{$MGLANG->T('Processor Name')}</th>
                                <th>{$MGLANG->T('Vendor ID')}</th>
                                <th>{$MGLANG->T('Processor Speed (MHz)')}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$rawObject->info->cpus item=$cpu key=key name=cpu}
                                <tr>
                                    <td>{$cpu->model_name}</td>
                                    <td>{$cpu->vendor_id}</td>
                                    <td>{$cpu->mhz}</td>
                                </tr>
                            {/foreach}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lu-widget">
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">{$MGLANG->T("memoryinfo")}</div>
        </div>
    </div>
    <div class="lu-widget__body p-20">
        <div class="lu-tab-content pt-20">
            <div class="dataTables_wrapper no-footer">
                <div>
                    <table width="100%" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
                        <tbody>
                            <tr>
                                <th>{$MGLANG->T("totalmem")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->MemTotal)}</td>
                            </tr>
                            <tr>
                                <th>{$MGLANG->T("freemem")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->MemFree)}</td>
                            </tr>
                            <tr>
                                <th>{$MGLANG->T("availablemem")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->MemAvailable)}</td>
                            </tr>
                            <tr>
                                <th>{$MGLANG->T("totalswap")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->SwapTotal)}</td>
                            </tr>
                            <tr>
                                <th>{$MGLANG->T("freeswap")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->SwapFree)}</td>
                            </tr>
                            <tr>
                                <th>{$MGLANG->T("cachedswap")}</th>
                                <td>{$rawObject->bytesToHuman($rawObject->info->mem_info->SwapCached)}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lu-widget">
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">{$MGLANG->T("services")}</div>
        </div>
    </div>
    <div class="lu-widget__body p-20">
        <div class="lu-tab-content pt-20">
            <div class="dataTables_wrapper no-footer">
                <div>
                    <table width="100%" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">

                        <tbody>
                            {foreach from=$rawObject->info->services item=service key=servicename name=name}
                                <tr>
                                    <td>{$servicename}</td>
                                    <td>
                                    {if $service->info_str eq "Running"}
                                    <span class="badge disp:iblock radius:def border:a:def txt:medium wrap:nowrap -theme-safe -size-normal -align-start"><div class="badge-inner fx:dir:row fx:cross:stretch"><div class="fx:dir:row fx:cross:center fx:main:start fx:wrap:false fxi:grow:true" layout-gutter="0.5" style="margin-top: -0.5rem; margin-left: -0.5rem;"><span class="icon" style="margin-top: 0.5rem; margin-left: 0.5rem;"><svg version="1.1" viewBox="0 0 510 510" class="svg-icon svg-fill c:txt:safe" style="width: 14px; height: 14px;"><path fill="currentColor" _stroke="none" pid="0" d="M255 0C114.75 0 0 114.75 0 255s114.75 255 255 255 255-114.75 255-255S395.25 0 255 0zm-51 382.5L76.5 255l35.7-35.7 91.8 91.8 193.8-193.8 35.7 35.7L204 382.5z"></path></svg></span> <span class="badge-text type:content lineh:1" style="margin-top: 0.5rem; margin-left: 0.5rem;"><span>{$MGLANG->T("Running")}</span></span></div></div></span>
                                    {elseif $service->info_str eq "Stopped"}
                                    <span class="badge disp:iblock radius:def border:a:def txt:medium wrap:nowrap -theme-danger -size-normal -align-start"><div class="badge-inner fx:dir:row fx:cross:stretch"><div class="fx:dir:row fx:cross:center fx:main:start fx:wrap:false fxi:grow:true" layout-gutter="0.5" style="margin-top: -0.5rem; margin-left: -0.5rem;"><span class="icon" style="margin-top: 0.5rem; margin-left: 0.5rem;"><svg version="1.1" viewBox="0 0 246.027 246.027" class="svg-icon svg-fill c:txt:danger" style="width: 14px; height: 14px;"><path fill="currentColor" _stroke="none" pid="0" d="M242.751 196.508l-98.814-171.15c-4.367-7.564-12.189-12.081-20.924-12.081s-16.557 4.516-20.924 12.081L3.276 196.508c-4.368 7.564-4.368 16.596 0 24.161S15.465 232.75 24.2 232.75h197.629c8.734 0 16.556-4.516 20.923-12.08 4.367-7.565 4.366-16.597-.001-24.162zm-119.737 8.398c-8.672 0-15.727-7.055-15.727-15.727 0-8.671 7.055-15.726 15.727-15.726s15.727 7.055 15.727 15.726c-.001 8.673-7.056 15.727-15.727 15.727zm15.833-67.226c0 8.73-7.103 15.833-15.833 15.833s-15.833-7.103-15.833-15.833V65.013a7.5 7.5 0 0 1 7.5-7.5h16.667a7.5 7.5 0 0 1 7.5 7.5v72.667z"></path></svg></span> <span class="badge-text type:content lineh:1" style="margin-top: 0.5rem; margin-left: 0.5rem;"><span>{$MGLANG->T("Stopped")}</span></span></div></div></span>
                                    {else}
                                        <span class="badge disp:iblock radius:def border:a:def txt:medium wrap:nowrap -theme-primary -size-normal -align-start"><div class="badge-inner fx:dir:row fx:cross:stretch"><div class="fx:dir:row fx:cross:center fx:main:start fx:wrap:false fxi:grow:true" layout-gutter="0.5" style="margin-top: -0.5rem; margin-left: -0.5rem;"><span class="icon" style="margin-top: 0.5rem; margin-left: 0.5rem;"><svg version="1.1" viewBox="0 0 510 510" class="svg-icon svg-fill c:txt:primary" style="width: 14px; height: 14px;"><path fill="currentColor" _stroke="none" pid="0" d="M255 0C114.75 0 0 114.75 0 255s114.75 255 255 255 255-114.75 255-255S395.25 0 255 0zm25.5 382.5h-51v-153h51v153zm0-204h-51v-51h51v51z"></path></svg></span> <span class="badge-text type:content lineh:1" style="margin-top: 0.5rem; margin-left: 0.5rem;"><span>{$MGLANG->T("Installed")}</span></span></div></div></span>
                                    {/if}
                                    </td>
                                    <td>{$service->version}</td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="lu-widget">
    <div class="lu-widget__header">
        <div class="lu-widget__top lu-top">
            <div class="lu-top__title">{$MGLANG->T("loadavg")}</div>
        </div>
    </div>
    <div class="lu-widget__body p-20">
        <div class="lu-tab-content pt-20">
            <div class="dataTables_wrapper no-footer">
                <div>
                    <table width="100%" class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column table-striped">
                        <tbody>
                            <tr><th>{$MGLANG->T('load_1')}</th><td>{$rawObject->info->load->load_1}</td></tr>
                            <tr><th>{$MGLANG->T('load_5')}</th><td>{$rawObject->info->load->load_5}</td></tr>
                            <tr><th>{$MGLANG->T('load_15')}</th><td>{$rawObject->info->load->load_15}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{if ($isDebug eq true AND (count($MGLANG->getMissingLangs()) != 0))}
    <div class="lu-row" style="max-width: 95%;">
        {foreach from=$MGLANG->getMissingLangs() key=varible item=value}
            <div class="lu-col-md-12"><b>{$varible}</b> = '{$value}';</div>
        {/foreach}
    </div>
{/if}
