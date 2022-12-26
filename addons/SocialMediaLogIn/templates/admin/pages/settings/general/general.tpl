{block name=head}
    <link rel="stylesheet" media="screen" type="text/css" href="../modules/addons/SocialMediaLogIn/templates/admin/assets/css/color-picker.css" />
{/block}

<style>
    .row {
        border-top: 1px solid #ddd; 
        line-height: 1.42857; 
        padding: 8px; 
        padding-top: 16px; 
        cursor: pointer;    
    }
    #firstRow{
        border-top: 0; 
        cursor: default;
    }   
    .color-picker{
        width:60%;
    }
    .color-picker-global{
        width:15%;
    }
    .fa-bars{
        padding-right: 20px;   
    } 
</style>

{if $saveSuccess}
    <div class="alert alert-success" role="alert">
        {{$MGLANG->T($saveSuccess)}}
        <button type="button" class="customMgclose close" data-dismiss="modal" aria-label="Close" >&times;</button>
    </div>
{/if}

<form action="" method="post">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Icon Sets')}</h3>
        </div> 
        <div class="panel-body">
            <div class="form-group">
                <div class="col-md-4">
                    <p>{$MGLANG->T('Select icon sets')}</p>
                    <select name="selectIconSets" class="form-control" id="iconSets" style="width: auto;">
                        {foreach from=$providerStyle item=styleName}
                            <option value="{$styleName}"{if $currentStyle == $styleName}selected="{$styleName}"{/if}>{$styleName}</option> 
                        {/foreach}
                    </select> 
                </div>
                <div class="col-md-4">
                    <p>{$MGLANG->T('Select mini icon sets')}</p>
                    <select name="selectMiniIconSets" class="form-control" id="miniIconSets" style="width: auto;">
                        {foreach from=$miniProviderStyle item=miniStyleName}
                            <option value="{$miniStyleName}"{if $miniCurrentStyle == $miniStyleName}selected="{$miniStyleName}"{/if}>{$miniStyleName}</option> 
                        {/foreach}
                    </select>     
                </div>           
            </div> 
        </div>                             
    </div>

    <div class="panel panel-primary">             
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Default colors')}</h3>
        </div>
        <div class="panel-body">  
            <div class="col-md-4">  
                <p>{$MGLANG->T('Select color of buttons')}</p>
                <div class="input-group colorpicker-component color-picker color-picker-global">  
                    <input style="width: auto;" type="text" class="form-control" name="colorPickerButtons" value="{$buttonsColor}"/>
                    <span class="input-group-addon"><i></i></span>
                </div>         
            </div>
            <div class="col-md-4">  
                <p>{$MGLANG->T('Select color on hover')}</p>
                <div class="input-group colorpicker-component color-picker color-picker-global">  
                    <input style="width: auto;" type="text" class="form-control" name="colorPickerButtonsOnHover" value="{$buttonsColorOnHover}"/>
                    <span class="input-group-addon"><i></i></span>
                </div>              
            </div>
            <div class="col-md-4">  
                <p>{$MGLANG->T('Select color of icons')}</p>
                <div class="input-group colorpicker-component color-picker color-picker-global">  
                    <input style="width: auto;" type="text" class="form-control" name="colorPickerIcons" value="{$iconsColor}"/>
                    <span class="input-group-addon"><i></i></span>
                </div>              
            </div>
        </div>           
    </div>

    <div class="panel panel-primary">             
        <div class="panel-heading">
            <h3 class="panel-title">{$MGLANG->T('Providers settings')}</h3>
        </div>
        <div class="panel-body">
            <div class="row" id="firstRow">
                <span class="col-md-2 text-left">
                    <strong>{$MGLANG->T('Active provider')}</strong>
                </span>
                <span class="col-md-2 text-left">
                    <strong>{$MGLANG->T('Button color')}</strong>
                </span>
                <span class="col-md-2 text-left">
                    <strong>{$MGLANG->T('Button color on hover')}</strong>
                </span>
                <span class="col-md-2 text-left">
                    <strong>{$MGLANG->T('Icon color')}</strong>
                </span>
                <span class="col-md-2 text-left">
                    <strong>{$MGLANG->T('Display name')}</strong>
                </span>
                <span class="col-md-2 text-left"></span>
            </div>
            <div id="allRows">   
                {if !$providers}
                    <div class="row" style="text-align:center;">{$MGLANG->T('Nothing to display')}</div>                            
                {/if} 

                {foreach from=$providers item=provider}        

                    {if isset($provider->configuration->order)}
                        <div class="row" id="row_{$provider->configuration->getId()}"> 
                            <span class="col-md-2 text-left">
                                <i class="fa fa-bars" aria-hidden="true"></i>{$MGLANG->T({$provider->getFriendlyName()})}
                            </span>    
                            <span class="col-md-2 text-left">
                                {if empty($provider->configuration->button_color)}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerButtonColor{$provider->getFriendlyName()}" value="{$buttonsColor}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>   
                                {else}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerButtonColor{$provider->getFriendlyName()}" value="{$provider->configuration->button_color}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>    
                                {/if}  
                            </span>
                            <span class="col-md-2 text-left">
                                {if empty($provider->configuration->button_hover_color)}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerButtonHoverColor{$provider->getFriendlyName()}" value="{$buttonsColorOnHover}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>    
                                {else}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerButtonHoverColor{$provider->getFriendlyName()}" value="{$provider->configuration->button_hover_color}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>    
                                {/if}  
                            </span> 
                            <span class="col-md-2 text-left">
                                {if empty($provider->configuration->icon_color)}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerIconColor{$provider->getFriendlyName()}" value="{$iconsColor}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>    
                                {else}
                                    <div class="input-group colorpicker-component color-picker">  
                                        <input type="text" class="form-control" name="colorPickerIconColor{$provider->getFriendlyName()}" value="{$provider->configuration->icon_color}"/>
                                        <span class="input-group-addon"><i></i></span>
                                    </div>    
                                {/if}  
                            </span>  
                            <span class="col-md-2 text-left">
                                <div class="input-group">  
                                    <input type="text" class="form-control" name="customName{$provider->getFriendlyName()}" value="{$provider->configuration->custom_name}" placeholder="{$MGLANG->T({$provider->getFriendlyName()})}"/>
                                 </div>    
                            </span>  
                            <span class="col-md-2 text-left">
                                <div class="btn btn-primary btn-sm" name="useGlobalColors" value="{$provider->configuration->getId()}">{$MGLANG->T('Use default')}</div>                            
                            </span> 
                        </div>  
                    {/if}   
                {/foreach}          
            </div>
        </div>
    </div>

    <div class="panel-body" style="text-align: right;">
        <button type="submit" class="btn btn-success active" >{$MGLANG->T('Save changes')}</button> 
    </div>
</form>  
{literal}
    <script type="text/javascript" src="../modules/addons/SocialMediaLogIn/templates/admin/assets/js/color-picker.js"></script>
    <script>
        $(function () {
            $('.color-picker').colorpicker();
            $("input:text").keypress(function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
            });
        });

        $(function () {
            $("#allRows").sortable({
                update: function (event, ui) {

                    var data = $('#allRows').sortable('serialize');
                    var formData = {rows: data};
                    JSONParser.request('saveOrder', formData, function (data) {});
                },
            });
        });

        $(function () {
            $("div[name='useGlobalColors']").click(function () {
                
                if(!confirm("{/literal}{$MGLANG->T('defaultSettingConfirm')}{literal}"))
                {
                    return;
                }
                var dataProvider = {};
                dataProvider["provider"] = $(this).attr("value");
                JSONParser.request('setGlobalColors', dataProvider, function (setDefaultColorsPerProvider) {

                    var providerName = setDefaultColorsPerProvider['provider'];
                    $("input[name='colorPickerButtonColor" + providerName + "'").colorpicker({color: setDefaultColorsPerProvider['button_color']});
                    $("input[name='colorPickerButtonHoverColor" + providerName + "'").colorpicker({color: setDefaultColorsPerProvider['button_hover_color']});
                    $("input[name='colorPickerIconColor" + providerName + "'").colorpicker({color: setDefaultColorsPerProvider['icon_color']});
                    $("input[name='customName" + providerName + "'").val('');
                });
            });
        });

        $('.close').click(function ()
        {
            removeMsg();
        });

        function removeMsg()
        {
            $('.customMgclose').parent().first().remove();
        }

/*
        $(document).ready(function ()
        {
            if ($('.customMgclose').is(':visible') === true)
            {
                setTimeout(function ()
                {
                    removeMsg();
                }, 3000);
            }
        })
*/

    </script>
{/literal}