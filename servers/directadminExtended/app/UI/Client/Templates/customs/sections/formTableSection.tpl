<div class="row {$rawObject->getId()}">
    <div class="{$rawObject->getContainerClasss()}">
        <table class="lu-table lu-table--mob-collapsible dataTable no-footer dtr-column">
            <thead>
                <tr>
                    <th>
                        <div class="lu-rail">
                            <div class="lu-form-check">
                                <label>
                                    <input type="checkbox" data-check-all="" class="lu-form-checkbox">
                                    <span class="lu-form-indicator"></span>
                                </label>
                            </div>
                            <span class="lu-table__text" >{$MGLANG->T('table', "Select All")}</span>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$rawObject->getItems() item=$item}

                    <tr role="row">
                        {foreach from=$item item=$data}
                            <td>
                                <div class="lu-form-check">
                                <label>
                                    <input type="checkbox"  class="lu-form-checkbox table-mass-action-check" name="users[]" value="{$data}">
                                    <span class="lu-form-indicator"></span>
                                    <span class="checklabel">{$data}</span>
                                </label>

                            </div> </td>
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>

    </div>
</div>
