<table class="table" id="providers-table">
    <thead>
        <tr>
            <th>{$MGLANG->T('table_header','provider')}</th>
            <th>{$MGLANG->T('table_header','status')}</th>
            <th style="width: 250px;">{$MGLANG->T('table_header','configure')}</th>
        </tr>
    </thead>
    <tbody>
    {foreach from=$providers item=provider}
        <tr>
            <td>{$MGLANG->T({$provider->getFriendlyName()})}</td>
            <td>
                {if $provider->configuration->getStatus()}
                    <span class="label label-success">
                {else}    
                    <span class="label label-danger">
                {/if}
                    {$provider->configuration->getStatusText()}
                </span>
            </td>
            <td>
                <a class="btn btn-primary btn-icon-only" data-act="editConfiguration" data-query="id={$provider->configuration->getId()}"><i class="fa fa-pencil"></i></a>
                {if $provider->configuration->getStatus()}
                    <a class="btn btn-warning btn-icon-only" data-act="disableProvider" data-query="id={$provider->configuration->getId()}"><i class="fa fa-power-off"></i></a>
                {else}    
                    <a class="btn btn-success btn-icon-only" data-act="enableProvider" data-query="id={$provider->configuration->getId()}"><i class="fa fa-power-off"></i></a>
                {/if}

            </td>
        </tr>
    {foreachelse}
        <tr><td colspan="2" class="center-text">{$MGLANG->T('table', 'empty')}</tr>
    {/foreach}
    </tbody>
</table>
