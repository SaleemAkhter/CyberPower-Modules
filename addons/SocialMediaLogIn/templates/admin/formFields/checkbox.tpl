{if $enableLabel}
    <label {if $id} for="{$id}" {elseif $addIDs}for="{$addIDs}_{$name}"{/if} class="col-sm-{$labelcolWidth} control-label">{$MGLANG->T('label')}</label>
{/if}
<div class="checkbox col-sm-{$colWidth}" {if $id} id="{$id}" {elseif $addIDs}id="{$addIDs}_{$name}"{/if}>
    {foreach from=$options item=option key=opValue}
        <div class="checkbox">
            <label>
                <input type="checkbox" {if is_array($value) && in_array($opValue,$value)}checked="checked"{/if} name="{$nameAttr}[]" value="{$opValue}" {if $disabled}disabled="disabled"{/if} {foreach from=$dataAttr key=dataKey item=dataValue}data-{$dataKey}="{$dataValue}"{/foreach}/>
                {$option}
            </label>
        </div>
    {/foreach}
    {if $enableDescription }
      <span class="help-block">{$MGLANG->T('description')}</span>
    {/if}
    <span class="help-block error-block"{if !$error}style="display:none;"{/if}>{$error}</span>
</div>