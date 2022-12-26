{$headerhtml}
{$menu}

<style>

</style>
 
 
<div class="cfcontent">
    <h3 class="cfcontentmargin">{$wgs_lang.cf_pagerules_manage}</h3>
    
</div>
<div id="deleterule-loader" style="display:none;color: red;"><i class='fas fa-spinner fa-pulse'></i></div>
         <div id="delete-error" style=""> </div>
<div id="delete-rule" style="display:none;"></div>
<div><h4>{$wgs_lang.cf_pagerules_heading}</h4></div>
<div class="col-md-12 page-rules ">
<div class="col-md-8  page-rule-content">
  <h4>Page Rules</h4>
  <p>Page Rules let you control which Cloudflare settings trigger on a given URL. Only one Page Rule will trigger per URL, so it is helpful if you sort Page Rules in priority order, and make your URL patterns as specific as possible. </p>

</div>

 <div class="col-md-4 page-rule-btn"> <button type="button" class="btn btn-primary create-rule"  onclick="rulemodal();"><span>Create Page Rule</span></button></div>
 </div>
<div id="rule-list" class="page-rules">
 
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">URL/Description</th>
      <th scope="col"> </th>
      <th scope="col"> </th>
    </tr>
  </thead>
  <tbody>
  {$counter=0}
  
    {foreach from=$pagerulelist key=k item=getdata}
     <tr>
        <th  width="5%">{counter}</th>
        <td  width="75%">{$getdata.url} <br> {foreach from=$getdata.pageruleall name=items key=valuekey item=rulevaldata}  {$rulevaldata.rulename|ucfirst}: {foreach from=$rulevaldata.ruleval name=valitems key=valkey item=subruleval}  {if $subruleval}{$subruleval|ucfirst} {/if}{if $smarty.foreach.valitems.last}{else}, {/if}  {/foreach} <br>  {/foreach} </td>
        <td width="10%"><button class="btn btn-warning" onclick="updatepagerule('{$getdata.pageruleid}');"><span><i class="fas fa-wrench"></i></span></button></td>
        <td width="10%"><button class="btn btn-danger" onclick="deletepagerule('{$getdata.pageruleid}');"><span><i class="fas fa-times"></i></span></button></td>
     </tr>
    {/foreach}
  </tbody>
</table>
 </div>
</div>


<!-- create rule Modal -->
<div id="modal-pagerule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <div id="pagerule-loader" style="display:none;color: red;"><i class='fas fa-spinner fa-pulse'></i></div>
         <div id="rule-error" style=""> </div>
        <h4 class="modal-title">Create a Page Rule for <b> {$domain} </b></h4>
       
      </div>
      <div class="modal-body">
        <div><p><b>If the URL matches:</b> By using the asterisk (*) character, you can create dynamic patterns that can match many URLs, rather than just one. All URLs are case insensitive.</p>
        </div>
        <form id="pagerules-form">
        
        <div class="form-group">
          <input type="text" placeholder="Example: www.againwgsdevtest.xyz/*" name="targeturl" class="form-control" >
        </div>
        <hr>
        <div class="form-group">
        <label>Then the settings are:</label><br>
        <div class="col-md-8 rules" id="pagerule1">
         
          <select id="pagerulesettings" name="pagerulesettings[]"    class="form-control pagerulesettings" required>
            <option value="" >Pic a Setting</option>
            <option value="always_online">Always Online</option>
            <option value="minify">Auto Minify</option>
            <option value="browser_cache_ttl">Browser Cache TTL</option>
            <option value="browser_check">Browser Integrity Check</option>
            <option value="cache_deception_armor">Cache Deception Armor</option>
            <option value="cache_level">Cache Level</option>
            <option value="disable_apps">Disable Apps</option>
            <option value="disable_performance">Disable Performance</option>
            <option value="disable_railgun">Disable Railgun</option>
            <option value="disable_security">Disable Security</option>
            <option value="edge_cache_ttl">Edge Cache TTL</option>
            <option value="email_obfuscation">Email Obfuscation</option>
            <option value="forwarding_url">Forwarding URL</option>
            <option value="ip_geolocation_header">IP Geolocation Header</option>
            <option value="origin_cache_control">Origin Cache Control</option>
            <option value="rocket_loader">Rocket Loader</option>
            <option value="security_level">Security Level</option>
            <option value="server_side_excludes">Server Side Excludes</option>
            <option value="ssl">SSL</option>
          </select>
       
        </div>
        <div class="col-md-4 subrule" id="subrule1">

        </div>
        <div id="pagerule2"></div>
        </div>
        <br>
        <div class="addnewsettingbtn">
        <button class="btn btn-secondary addnewsetting" id="addnewsetting"  onclick="return false;">+ Add a Setting</button>
      </div>
      <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"   onclick="pagerules();">Save and Deploy</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="this.form.reset();">Close</button>
      </div>
    </div>
 </form>
  </div>
</div>

</div>
<!-- The update Modal -->
<div class="modal" id="updaterulemodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
         <div id="pageruleupdate-loader" style="display:none;color: red;"><i class='fas fa-spinner fa-pulse'></i></div>
         <div id="updaterule-error" style=""> </div>
        <h4 class="modal-title">Update a Page Rule for <b> {$domain} </b></h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
          <div><p><b>If the URL matches:</b> By using the asterisk (*) character, you can create dynamic patterns that can match many URLs, rather than just one. All URLs are case insensitive.</p>
        </div>
        <form id="pagerulesupdate-form">
        <input name="ruleid"  id="updtruleid" type="hidden"  >
        <div class="form-group">
          <input type="text" placeholder="Example: www.againwgsdevtest.xyz/*"  id="geturlval" name="targeturl" class="form-control" >
        </div>
        <hr>
        <div class="form-group">
        <label>Then the settings are:</label><br>
        <div class="col-md-8 rules" id="pageruleupdate1">
         
          <select id="pagerulesettingsupdate" name="pagerulesettings[]"    class="form-control pagerulesettingsupdate" required>
            <option value="" >Pic a Setting</option>
            <option value="always_online">Always Online</option>
            <option value="minify">Auto Minify</option>
            <option value="browser_cache_ttl">Browser Cache TTL</option>
            <option value="browser_check">Browser Integrity Check</option>
            <option value="cache_deception_armor">Cache Deception Armor</option>
            <option value="cache_level">Cache Level</option>
            <option value="disable_apps">Disable Apps</option>
            <option value="disable_performance">Disable Performance</option>
            <option value="disable_railgun">Disable Railgun</option>
            <option value="disable_security">Disable Security</option>
            <option value="edge_cache_ttl">Edge Cache TTL</option>
            <option value="email_obfuscation">Email Obfuscation</option>
            <option value="forwarding_url">Forwarding URL</option>
            <option value="ip_geolocation_header">IP Geolocation Header</option>
            <option value="origin_cache_control">Origin Cache Control</option>
            <option value="rocket_loader">Rocket Loader</option>
            <option value="security_level">Security Level</option>
            <option value="server_side_excludes">Server Side Excludes</option>
            <option value="ssl">SSL</option>
          </select>
        </div>

        <div class="col-md-4 subrule" id="subruleupdate1">
           
        </div>

        <div id="pageruleupdate2"></div>
         </div>
        <br>
        <div class="addnewsettingbtn">
        <button class="btn btn-secondary"  id="addnewsettingupdate" onclick="return false;">+ Add a Setting</button>
       </div>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary"   onclick="updatepagerulessave();">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="this.form.reset();">Close</button>
      </div>

    </div>
  </div>
</div>
<script>
 
$(document).ready(function() {
    
     $(document).on('change','#pagerulesettings',function(){
          var  pagerule = $('option:selected', this).val();
          jQuery('#div-forwarding_url_destination').remove();
           /*$('.rules').addClass( "col-md-8");
          $('.subrule').addClass( "col-md-4");
          $('.rules').removeClass( "col-md-6");
          $('.subrule').removeClass( "col-md-6");*/
          $('#addnewsetting').prop("disabled", false);
          if(pagerule == ''){
            jQuery(this).parent().next().html('');
          }
           
          if(pagerule == 'always_online'){

            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');

          }

          if(pagerule == 'minify'){
           jQuery(this).parent().next().html('<div class="minifybox"><label for=" " id="minify-lbl">HTML</label><select id="minify-html" name="subrule[minify][html]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div>  <div class="minifybox"><label for=" " id="minify-lbl">CSS</label><select id="minify-css" name="subrule[minify][css]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div> <div class="minifybox"> <label for=" " id="minify-lbl">JS</label> <select id="minify-js" name="subrule[minify][js]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select> </div> ');
          }

          if(pagerule == 'browser_cache_ttl'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="browsertldelect" ><option >Enter Browser Cache Ttl</option><option value="1800" >30 minutes</option><option value="3600" >an hour</option><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option> <option value="691200">8 day</option><option value="1382400">16 day</option><option value="2073600">24 day</option><option value="2678400">a month</option><option value="5356800" >2 month</option><option value="16070400">6 month</option><option value="31536000">a year</option></select>');
          }

          if(pagerule == 'browser_check'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'cache_deception_armor'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'cache_level'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="sub_cache_level" ><option value="bypass" >By Pass</option><option value="basic">No Query String</option><option value="simplified">Ignore Query String</option><option value="aggressive" >Standard</option><option value="cache_everything">Cache Everything</option></select>');
          } 
          if(pagerule == 'disable_apps'){
            jQuery(this).parent().next().html('Apps are disabled <input type="text" name="subrule[]" style="display:none;">');
          } 
          if(pagerule == 'disable_performance'){
            jQuery(this).parent().next().html('Performance is disabled<input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_railgun'){
            jQuery(this).parent().next().html('Railgun is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_security'){
            jQuery(this).parent().next().html('Security is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'edge_cache_ttl'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="sub_edge_cache" ><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option><option value="518400">6 day</option><option value="604800">7 day</option><option value="1209600">14 day</option><option value="2678400">a month</option> </select>');
          } 
          if(pagerule == 'email_obfuscation'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'ip_geolocation_header'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'origin_cache_control'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'rocket_loader'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'server_side_excludes'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'ssl'){
            jQuery(this).parent().next().html('<select name="subrule[]"  class="form-control" id="sub_ssl" ><option value="off" >Off</option><option value="flexible">Flexible</option><optionvalue="full" >Full</option><option value="strict" >Strict</option></select>');
          } 
          if(pagerule == 'security_level'){
            jQuery(this).parent().next().html("<select  name='subrule[]'  class='form-control' id='sub_security_level' ><option value='essentially_off'>Essentially Off</option><option value='low' >Low</option><option value='medium' >Medium</option><option value='high'>High</option><option value='under_attack'>I'm Under Attack </option></select>");
          }  
          if(pagerule == 'forwarding_url'){
           /* $('.rules').removeClass( "col-md-8");
            $('.subrule').removeClass( "col-md-4");
             $('.rules').addClass( "col-md-6");
             $('.subrule').addClass( "col-md-6 frwd");*/
            jQuery(this).parent().next().html("<select  name='subrule[forwarding_url][status_code]' class='form-control' id='sub_forwarding_url_statuscode' ><option value='301' >301 - Permannent Off</option><option value='302'>302 - Temporary</option> </select>");
            jQuery(this).parent().next().next().html("<div id='div-forwarding_url_destination'> <input type='text' placeholder='Enter destination URL' name='subrule[forwarding_url][url]'  class='form-control' value=''> <span><b>You cannot add any additional settings with 'Forwarding URL' selected.</b></span></div> ");
            $('#addnewsetting').prop("disabled", true);
          } 
     });

      var counter = 1;
       
      $(".addnewsetting").click(function(i, val){
          
          var forwadingurl = $(".pagerulesettings").val();
          if(forwadingurl == 'forwarding_url'){

          }else{
            //  $('#removerowrule1').remove();
            $('#div-forwarding_url_destination').remove();
            var v = [];
            var getselectbox = jQuery('#pagerule1').html();  
      
            jQuery('#closeicon').remove();
            counter++;

            jQuery('#pagerule2').append('<div class="col-md-8 mutliplerule" id="rule'+counter+'">'+getselectbox+'</div><div id="subrule'+counter+'" class="col-md-4"></div>  <span class="close removerow" id="removerow" removeid="'+counter+'">&times;</span>');

            //  $('#subrule1').after('<span class="close removerow" id="removerowrule1" removeid="1">&times;</span>');

            jQuery(".pagerulesettings").each(function(){
              v.push($(this).val());

              var seletedvalue = $(this).val();
              if(seletedvalue != ""){
                jQuery('#rule'+counter).find("option[value='"+seletedvalue+"']").remove();
                jQuery('#rule'+counter).find("option[value='forwarding_url']").prop('disabled',true);
             }
            });
          }
      });

        $(document).on('change','#pagerulesettingsupdate',function(){
          var  pagerule = $('option:selected', this).val();
          
          jQuery('#div-forwarding_url_destination').remove();
          
          if(pagerule == ''){
            jQuery(this).parent().next().html('');
          }
          if(pagerule == 'always_online'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }

          if(pagerule == 'minify'){
            jQuery(this).parent().next().html('<div class="minifybox"><label for=" " id="minify-lbl">HTML</label><select id="minify-html" name="subrule[minify][html]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div>  <div class="minifybox"><label for=" " id="minify-lbl">CSS</label><select id="minify-css" name="subrule[minify][css]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div> <div class="minifybox"> <label for=" " id="minify-lbl">JS</label> <select id="minify-js" name="subrule[minify][js]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select> </div> ');
          }

          if(pagerule == 'browser_cache_ttl'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="browsertldelect" ><option >Enter Browser Cache Ttl</option><option value="1800" >30 minutes</option><option value="3600" >an hour</option><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option> <option value="691200">8 day</option><option value="1382400">16 day</option><option value="2073600">24 day</option><option value="2678400">a month</option><option value="5356800" >2 month</option><option value="16070400">6 month</option><option value="31536000">a year</option></select>');
          }

          if(pagerule == 'browser_check'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'cache_deception_armor'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'cache_level'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="sub_cache_level" ><option value="bypass" >By Pass</option><option value="basic">No Query String</option><option value="simplified">Ignore Query String</option><option value="aggressive" >Standard</option><option value="cache_everything">Cache Everything</option></select>');
          } 
          if(pagerule == 'disable_apps'){
            jQuery(this).parent().next().html('Apps are disabled <input type="text" name="subrule[]" style="display:none;">');
          } 
          if(pagerule == 'disable_performance'){
            jQuery(this).parent().next().html('Performance is disabled<input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_railgun'){
            jQuery(this).parent().next().html('Railgun is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_security'){
            jQuery(this).parent().next().html('Security is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'edge_cache_ttl'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control" id="sub_edge_cache" ><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option><option value="518400">6 day</option><option value="604800">7 day</option><option value="1209600">14 day</option><option value="2678400">a month</option> </select>');
          } 
          if(pagerule == 'email_obfuscation'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'ip_geolocation_header'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'origin_cache_control'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'rocket_loader'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'server_side_excludes'){
            jQuery(this).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'ssl'){
            jQuery(this).parent().next().html('<select name="subrule[]"  class="form-control" id="sub_ssl" ><option value="off" >Off</option><option value="flexible">Flexible</option><optionvalue="full" >Full</option><option value="strict" >Strict</option></select>');
          } 
          if(pagerule == 'security_level'){
            jQuery(this).parent().next().html("<select  name='subrule[]'  class='form-control' id='sub_security_level' ><option value='essentially_off'>Essentially Off</option><option value='low' >Low</option><option value='medium' >Medium</option><option value='high'>High</option><option value='under_attack'>I'm Under Attack </option></select>");
          }  
          if(pagerule == 'forwarding_url'){
           /* $('.rules').removeClass( "col-md-8");
            $('.subrule').removeClass( "col-md-4");
             $('.rules').addClass( "col-md-6");
             $('.subrule').addClass( "col-md-6 frwd");*/
            jQuery(this).parent().next().html("<select  name='subrule[forwarding_url][status_code]' class='form-control' id='sub_forwarding_url_statuscode' ><option value='301' >301 - Permannent Off</option><option value='302'>302 - Temporary</option> </select>");
            jQuery(this).parent().next().next().html("<div id='div-forwarding_url_destination'> <input type='text' placeholder='Enter destination URL' name='subrule[forwarding_url][url]'  class='form-control' value=''> <span><b>You cannot add any additional settings with 'Forwarding URL' selected.</b></span></div> ");
          } 
    });

      $("#addnewsettingupdate").click(function(i, val){
          
          var forwadingurl = $(".pagerulesettingsupdate").val();
          if(forwadingurl == 'forwarding_url'){

          }else{
           // $('#removerowruleupdate1').remove();
            $('#div-forwarding_url_destination').remove();
            var v = [];
            var getselectbox = jQuery('#rule20').html();  
            //jQuery('#closeicon').remove();
            counter++;

            jQuery('#pageruleupdate2').append('<div class="col-md-8 mutliplerule" id="rule'+counter+'">'+getselectbox+'</div><div id="subrule'+counter+'" class="col-md-4"></div>  <span class="close removerow" id="removerowupdate" removeid="'+counter+'">&times;</span>');

            $('#subrule1').after('<span class="close removerow" id="removerowrule1" removeid="1">&times;</span>');

            jQuery(".pagerulesettings").each(function(){
              v.push($(this).val());

              var seletedvalue = $(this).val();

              if(seletedvalue != ""){
                jQuery('#rule'+counter).find("option[value='"+seletedvalue+"']").remove();
                jQuery('#rule'+counter).find("option[value='forwarding_url']").prop('disabled',true);
             }
            });
          }

      });

      $(document).on('click','.removerow',function(){
        var rowid = $(this).attr('removeid');
        
        if(rowid == 1){
          jQuery("#pagerule1 option:selected").prop("selected", false);
          jQuery('#pagerule1').hide();
          jQuery('#pagerulefield2').remove();
          jQuery(this).remove();
        }else{
          jQuery('#rule'+rowid).remove();
          jQuery('#subrule'+rowid).remove();
          jQuery(this).remove();
        }
      });

});

 

  function pagerules(){
      jQuery('#rule-error').html('');
      jQuery('#pagerule-loader').show();

    var formdata = jQuery("#pagerules-form").serialize();
    $.ajax({
      url: "",
      type: "POST",
      data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={$domain}&cfaction=pagerules&customAction=insertpageruledata&"+formdata,
      success: function(data)
      {
      jQuery('#pagerule-loader').hide();
        console.log(data);
        if(data =='success'){
          jQuery('#rule-error').html('<div class="alert alert-success" role="alert" style=" text-align: center;">Page rule has been successfully Added!</div>');
          
          setTimeout(function() {
            location.reload();
          }, 3000);  
        }else{
          jQuery('#rule-error').html('<div class="alert alert-danger" role="alert" style=" text-align: center;">'+data+'</div>');  
        }
      }
      });
    }

    function rulemodal(){
        jQuery('#rule-error').html('');
        jQuery('#modal-pagerule').modal('show');
    }

    function deletepagerule(pageid){
      if (confirm('Are you sure you want to delete this?')){
        jQuery('#deleterule-loader').show();
        $.ajax({
        url: "",
        type: "POST",
        data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={$domain}&cfaction=pagerules&customAction=delpagerule&pageid="+pageid,
        success: function(data)
        {
          jQuery('#deleterule-loader').hide();
          if(data =='deleted'){
            jQuery('#delete-rule').html('<div class="alert alert-success" role="alert" style=" text-align: center;">Page rule deleted successfully!</div>');  
            setTimeout(function() {
              location.reload();
            }, 3000); 
          }else{
            jQuery('#delete-rule').html('<div class="alert alert-danger" role="alert" style=" text-align: center;">'+data+'</div>');  
          }
          jQuery('#delete-rule').show();

        }

        });
      }
      
    }

     
    function updatepagerule(pageid){

      $('#updaterule-error').html('');
      jQuery('#pageruleupdate-loader').show();
      jQuery('#pageruleupdate2').html('');

      jQuery('#updaterulemodal').modal('show');

      jQuery('#updtruleid').val(pageid);

      $.ajax({
        url: "",
        type: "POST",
        data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={$domain}&cfaction=pagerules&customAction=getruledetail&ruleid="+pageid,
        success: function(data)
        {
          var getdata = JSON.parse(data);
  
        var geturl = getdata.targeturl;
        var getrules = getdata.rules;

        jQuery('#geturlval').val(geturl);
        var getselectbox = jQuery('#pagerule1').html();  
        $('#pagerulesettingsupdate').hide();
        var counter = 19;
        $('#addnewsettingupdate').prop("disabled", false);
        $.each(getrules, function(i, item) {
          var rule = item.id;
          var val = item.value;
          counter++; 
          var selectid = "#rule"+counter+" select";
    
          if(counter == 20){
            jQuery('#pageruleupdate2').append('<div class="col-md-8 mutliplerule" id="rule'+counter+'">'+getselectbox+'</div><div id="subrule'+counter+'" class="col-md-4"></div>');
          }else{
             jQuery('#pageruleupdate2').append('<div class="col-md-8 mutliplerule" id="rule'+counter+'">'+getselectbox+'</div><div id="subrule'+counter+'" class="col-md-4"></div>  <span class="close removerow" id="removerowupdate" removeid="'+counter+'">&times;</span>');
          }
          jQuery(selectid).val(rule);
          var pagerule = rule;
         
          if(pagerule == 'always_online'){
            jQuery(selectid).parent().next().html('<select id="subruleupt'+counter+'"  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
           
          }

          if(pagerule == 'minify'){
            jQuery(selectid).parent().next().html('<div class="minifybox"><label for=" " id="minify-lbl">HTML</label><select id="minify-html" name="subrule[minify][html]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div>  <div class="minifybox"><label for=" " id="minify-lbl">CSS</label><select id="minify-css" name="subrule[minify][css]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select></div> <div class="minifybox"> <label for=" " id="minify-lbl">JS</label> <select id="minify-js" name="subrule[minify][js]" class="form-control" ><option value="off" >Off</option><option value="on" >On</option> </select> </div> ');
          }

          if(pagerule == 'browser_cache_ttl'){
            jQuery(selectid).parent().next().html('<select  name="subrule[]" class="form-control" id="subruleupt'+counter+'" ><option >Enter Browser Cache Ttl</option><option value="1800" >30 minutes</option><option value="3600" >an hour</option><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option> <option value="691200">8 day</option><option value="1382400">16 day</option><option value="2073600">24 day</option><option value="2678400">a month</option><option value="5356800" >2 month</option><option value="16070400">6 month</option><option value="31536000">a year</option></select>');
          }

          if(pagerule == 'browser_check'){
            jQuery(selectid).parent().next().html('<select id="subruleupt'+counter+'"  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          }
          if(pagerule == 'cache_deception_armor'){
            jQuery(selectid).parent().next().html('<select  name="subrule[]" class="form-control"   ><option value="off" >Off</option><option value="on" >On</option> </select>');
          } 
          if(pagerule == 'cache_level'){
            jQuery(selectid).parent().next().html('<select  name="subrule[]" class="form-control" id="subruleupt'+counter+'" ><option value="bypass" >By Pass</option><option value="basic">No Query String</option><option value="simplified">Ignore Query String</option><option value="aggressive" >Standard</option><option value="cache_everything">Cache Everything</option></select>');
          } 
          if(pagerule == 'disable_apps'){
            jQuery(selectid).parent().next().html('Apps are disabled <input type="text" name="subrule[]" style="display:none;">');
          } 
          if(pagerule == 'disable_performance'){
            jQuery(selectid).parent().next().html('Performance is disabled<input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_railgun'){
            jQuery(selectid).parent().next().html('Railgun is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'disable_security'){
            jQuery(selectid).parent().next().html('Security is disabled <input type="text" name="subrule[]" style="display:none;">');
          }
          if(pagerule == 'edge_cache_ttl'){
            jQuery(selectid).parent().next().html('<select  name="subrule[]" class="form-control" id="subruleupt'+counter+'" ><option value="7200" >2 hour</option><option value="10800">3 hour</option><option value="14400" >4 hour</option><option value="18000">5 hour</option><option value="28800">8 hour</option><option value="43200">12 hour</option><option value="57600">16 hour</option><option value="72000">20 hour</option><option value="86400">a day</option><option value="172800">2 day</option><option value="259200">3 day</option><option value="345600">4 day</option><option value="432000">5 day</option><option value="518400">6 day</option><option value="604800">7 day</option><option value="1209600">14 day</option><option value="2678400">a month</option> </select>');
          } 
          if(pagerule == 'email_obfuscation'){
            jQuery(selectid).parent().next().html('<label class="switch"><input type="checkbox" name="subrule[]" id="subruleupt'+counter+'" ><span class="slider" id="btn-onoff">On Off</span></label>');
          }
          if(pagerule == 'ip_geolocation_header'){
            jQuery(selectid).parent().next().html('<label class="switch"><input type="checkbox" name="subrule[]" id="subruleupt'+counter+'"><span class="slider" id="btn-onoff">On Off</span></label>');
          }
          if(pagerule == 'origin_cache_control'){
            jQuery(selectid).parent().next().html('<label class="switch"><input type="checkbox" name="subrule[]" id="subruleupt'+counter+'"><span class="slider" id="btn-onoff">On Off</span></label>');
          } 
          if(pagerule == 'rocket_loader'){
            jQuery(selectid).parent().next().html('<label class="switch"><input type="checkbox" name="subrule[]" id="subruleupt'+counter+'"><span class="slider" id="btn-onoff">On Off</span></label>');
          } 
          if(pagerule == 'server_side_excludes'){
            jQuery(selectid).parent().next().html('<label class="switch"><input type="checkbox" name="subrule[]" id="subruleupt'+counter+'" ><span class="slider" id="btn-onoff">On Off</span></label>');
          }
          if(pagerule == 'ssl'){
            jQuery(selectid).parent().next().html('<select name="subrule[]"  class="form-control" id="subruleupt'+counter+'" ><option value="off" >Off</option><option value="flexible">Flexible</option><optionvalue="full" >Full</option><option value="strict" >Strict</option></select>');
          } 
          if(pagerule == 'security_level'){
            jQuery(selectid).parent().next().html("<select  name='subrule[]'  class='form-control' id='subruleupt"+counter+"' ><option value='essentially_off'>Essentially Off</option><option value='low' >Low</option><option value='medium' >Medium</option><option value='high'>High</option><option value='under_attack'>I'm Under Attack </option></select>");
          }  
          if(pagerule == 'forwarding_url'){
           /* $('.rules').removeClass( "col-md-8");
            $('.subrule').removeClass( "col-md-4");
             $('.rules').addClass( "col-md-6");
             $('.subrule').addClass( "col-md-6 frwd");*/
            jQuery(selectid).parent().next().html("<select  name='subrule[forwarding_url][status_code]' class='form-control' id='sub_forwarding_url_statuscode' ><option value='301' >301 - Permannent Off</option><option value='302'>302 - Temporary</option> </select>");
               jQuery('#pageruleupdate2').append("<div id='div-forwarding_url_destination'><input type='text' id='autofwdurl' placeholder='Enter destination URL' name='subrule[forwarding_url][url]'  class='form-control'> <span><b>You cannot add any additional settings with 'Forwarding URL' selected.</b></span></div>");
               $('#addnewsettingupdate').prop("disabled", true);
            
          } 
  
          var sbrulechk = "#subruleupt"+counter+""; 
          $('#subruleupt'+counter).val(item.value);
        
          if(typeof item.value =='object'){
            $.each(item.value, function( index, value ) {
              //alert( index + ": " + value );
              if(index == 'html' && value == 'on'){
                $('#minify-html').val(value);
              }
              if(index == 'css' && value == 'on'){
                $('#minify-css').val(value);
              }
              if(index == 'js' && value == 'on'){
                $('#minify-js').val(value);
              }
              if(index == 'url'){
                $('#autofwdurl').val(value);
              }
              if(index == 'status_code'){
                $('#sub_forwarding_url_statuscode').val(value);
              }
            });
          }

    

        });
          jQuery('#pageruleupdate-loader').hide();

        }

      });
     

    }

    function updatepagerulessave(){
      jQuery('#pageruleupdate-loader').show();
      $('#updaterule-error').html('');
      $('#pageruleupdate1').remove();
      $('#subruleupdate1').remove();
      var formdataupdate = jQuery("#pagerulesupdate-form").serialize();

      $.ajax({
      url: "",
      type: "POST",
      data: "modop=custom&a=ManageCf&cf_action=manageWebsite&website={$domain}&cfaction=pagerules&customAction=updatepagerule&"+formdataupdate,
      success: function(data)
      {
       
      jQuery('#pageruleupdate-loader').hide();

        if(data =='success'){
          jQuery('#updaterule-error').html('<div class="alert alert-success" role="alert" style=" text-align: center;">Page rule has been successfully Updated!</div>');
          
          setTimeout(function() {
              location.reload();
            }, 3000); 
        }else{
          jQuery('#updaterule-error').html('<div class="alert alert-danger" role="alert" style=" text-align: center;">'+data+'</div>');  
        }
      }
      });
   
    }
   

</script>

