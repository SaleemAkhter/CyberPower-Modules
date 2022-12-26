<div class="row {$rawObject->getId()}">
    <div class="{$rawObject->getContainerClasss()}">
        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column">
            <thead>
                {foreach from=$rawObject->getHeaders() item=$title}
                    <th>{$MGLANG->T($title)} </th>
                {/foreach}
            </thead>
            <tbody>
                {foreach from=$rawObject->getItems() item=$item}

                    <tr role="row">
                        {foreach from=$item item=$data}
                          {if $data@first}
                            <td>{$MGLANG->T($data)}</td>
                        {else}
                            <td>{$data}</td>
                        {/if}
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>

    </div>
</div>
