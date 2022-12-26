{if in_array('state', $optionalFields)}
    <script>
        var statesTab = 10;
        var stateNotRequired = true;
    </script>
{/if}

<script type="text/javascript" src="{$BASE_PATH_JS}/StatesDropdown.js"></script>
<script type="text/javascript" src="{$BASE_PATH_JS}/PasswordStrength.js"></script>
<script>
    window.langPasswordStrength = "{$LANG.pwstrength}";
    window.langPasswordWeak = "{$LANG.pwstrengthweak}";
    window.langPasswordModerate = "{$LANG.pwstrengthmoderate}";
    window.langPasswordStrong = "{$LANG.pwstrengthstrong}";
    jQuery(document).ready(function()
    {
        jQuery("#inputNewPassword1").keyup(registerFormPasswordStrengthFeedback);
    });
</script>
    <p id="nav-toggle"></p>

    <div class="fullrock config sec-bg2 motpath">
      <a onclick="window.history.go(-1); return false;" class="closebtn">
        <img class="svg closer bg-transparent" src="fonts/svg/close.svg" alt="">
      </a>
        <section class="fullrock-content">
            <div class="container">
                <a href="index"><img class="svg logo-menu" src="img/logo-light.svg" alt="logo Antler"></a>
                <div class="sec-main sec-bg1 tabs">
                    <div class="randomline">
                        <div class="bigline"></div>
                        <div class="smallline"></div>
                    </div>
                    <h2><b>Login or Create a New Account</b></h2>
                    <p class="mb-5">If you are a returning customer, please Login if not, create a new account.</p>
                    <div class="tabs-header btn-select-customer">
                        <ul class="btn-group">
                            <li class="btn btn-secondary mb-2">Already Registered?</li>
                            <li class="btn btn-secondary active">Create a New Account</li>
                        </ul>
                    </div>
                    <div class="{if !$linkableProviders}hidden{/if}">
                        {include file="$template/includes/linkedaccounts.tpl" linkContext="login" customFeedback=true}
                    </div>
                    <div class="providerLinkingFeedback mt-5"></div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table tabs-item active">
                                <div class="cd-filter-block mb-0">
                                    <h3>Sign Up</h3>
                                    <div class="cd-filter-content">
{if $registrationDisabled}
    {include file="$template/includes/alert.tpl" type="error" msg=$LANG.registerCreateAccount|cat:' <strong><a href="'|cat:"$WEB_ROOT"|cat:'/cart.php" class="alert-link">'|cat:$LANG.registerCreateAccountOrder|cat:'</a></strong>'}
{/if}

{if $errormessage}
    {include file="$template/includes/alert.tpl" type="error" errorshtml=$errormessage}
{/if}                                    
{if !$registrationDisabled}
                                        <form method="post" class="using-password-strength" action="{$smarty.server.PHP_SELF}" role="form" name="orderfrm" id="frmCheckout">
                                            <input type="hidden" name="register" value="true"/>

                                            <div id="containerNewUserSignup">
                                                <div class="sub-heading">
                                                    <h4>{$LANG.orderForm.personalInformation}</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputFirstName" class="field-icon">
                                                                <i class="fas fa-user"></i>
                                                            </label>
                                                            <input type="text" name="firstname" id="inputFirstName"  placeholder="{$LANG.orderForm.firstName}" value="{$clientfirstname}" {if !in_array('firstname', $optionalFields)}required{/if} autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputLastName" class="field-icon">
                                                                <i class="fas fa-user"></i>
                                                            </label>
                                                            <input type="text" name="lastname" id="inputLastName"  placeholder="{$LANG.orderForm.lastName}" value="{$clientlastname}" {if !in_array('lastname', $optionalFields)}required{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputEmail" class="field-icon">
                                                                <i class="fas fa-envelope"></i>
                                                            </label>
                                                            <input type="email" name="email" id="inputEmail"  placeholder="{$LANG.orderForm.emailAddress}" value="{$clientemail}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputPhone" class="field-icon">
                                                                <i class="fas fa-phone"></i>
                                                            </label>
                                                            <input type="tel" name="phonenumber" id="inputPhone"  placeholder="{$LANG.orderForm.phoneNumber}" value="{$clientphonenumber}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="sub-heading">
                                                    <h4>{$LANG.orderForm.billingAddress}</h4>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputCompanyName" class="field-icon">
                                                                <i class="fas fa-building"></i>
                                                            </label>
                                                            <input type="text" name="companyname" id="inputCompanyName"  placeholder="{$LANG.orderForm.companyName} ({$LANG.orderForm.optional})" value="{$clientcompanyname}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputAddress1" class="field-icon">
                                                                <i class="far fa-building"></i>
                                                            </label>
                                                            <input type="text" name="address1" id="inputAddress1"  placeholder="{$LANG.orderForm.streetAddress}" value="{$clientaddress1}"  {if !in_array('address1', $optionalFields)}required{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputAddress2" class="field-icon">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </label>
                                                            <input type="text" name="address2" id="inputAddress2"  placeholder="{$LANG.orderForm.streetAddress2}" value="{$clientaddress2}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputCity" class="field-icon">
                                                                <i class="far fa-building"></i>
                                                            </label>
                                                            <input type="text" name="city" id="inputCity"  placeholder="{$LANG.orderForm.city}" value="{$clientcity}"  {if !in_array('city', $optionalFields)}required{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <div class="form-group prepend-icon">
                                                            <label for="state" class="field-icon" id="inputStateIcon">
                                                                <i class="fas fa-map-signs"></i>
                                                            </label>
                                                            <label for="stateinput" class="field-icon" id="inputStateIcon">
                                                                <i class="fas fa-map-signs"></i>
                                                            </label>
                                                            <input type="text" name="state" id="state"  placeholder="{$LANG.orderForm.state}" value="{$clientstate}"  {if !in_array('state', $optionalFields)}required{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputPostcode" class="field-icon">
                                                                <i class="fas fa-certificate"></i>
                                                            </label>
                                                            <input type="text" name="postcode" id="inputPostcode"  placeholder="{$LANG.orderForm.postcode}" value="{$clientpostcode}" {if !in_array('postcode', $optionalFields)}required{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputCountry" class="field-icon" id="inputCountryIcon">
                                                                <i class="fas fa-globe"></i>
                                                            </label>
                                                            <select name="country" id="inputCountry" >
                                                                {foreach $clientcountries as $countryCode => $countryName}
                                                                    <option value="{$countryCode}"{if (!$clientcountry && $countryCode eq $defaultCountry) || ($countryCode eq $clientcountry)} selected="selected"{/if}>
                                                                        {$countryName}
                                                                    </option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {if $showTaxIdField}
                                                        <div class="col-sm-12">
                                                            <div class="form-group prepend-icon">
                                                                <label for="inputTaxId" class="field-icon">
                                                                    <i class="fas fa-building"></i>
                                                                </label>
                                                                <input type="text" name="tax_id" id="inputTaxId"  placeholder="{$taxLabel} ({$LANG.orderForm.optional})" value="{$clientTaxId}">
                                                            </div>
                                                        </div>
                                                    {/if}
                                                </div>
                                                {if $customfields || $currencies}
                                                <div class="sub-heading">
                                                    <h4>{$LANG.orderadditionalrequiredinfo}<br><i><small>{lang key='orderForm.requiredField'}</small></i></h4>
                                                </div>
                                                <div class="row">
                                                    {if $customfields}
                                                    {foreach $customfields as $customfield}
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="customfield{$customfield.id}">{$customfield.name} {$customfield.required}</label>
                                                                <div class="control">
                                                                    {$customfield.input}
                                                                {if $customfield.description}
                                                                    <span class="field-help-text">{$customfield.description}</h4>
                                                                {/if}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    {/foreach}
                                                    {/if}
                                                    {if $customfields && count($customfields)%2 > 0 }
                                                        <div class="clearfix"></div>
                                                    {/if}
                                                    {if $currencies}
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputCurrency" class="field-icon">
                                                                <i class="far fa-money-bill-alt"></i>
                                                            </label>
                                                            <select id="inputCurrency" name="currency" >
                                                                {foreach from=$currencies item=curr}
                                                                    <option value="{$curr.id}"{if !$smarty.post.currency && $curr.default || $smarty.post.currency eq $curr.id } selected{/if}>{$curr.code}</option>
                                                                {/foreach}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    {/if}
                                                </div>
                                                {/if}
                                            </div>
                                            <div id="containerNewUserSecurity" {if $remote_auth_prelinked && !$securityquestions } class="hidden"{/if}>

                                                <div class="sub-heading">
                                                    <h4>{$LANG.orderForm.accountSecurity}</h4>
                                                </div>
                                                <div id="containerPassword" class="row{if $remote_auth_prelinked && $securityquestions} hidden{/if}">
                                                    <div id="passwdFeedback" style="display: none;" class="alert alert-info text-center col-sm-12"></div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputNewPassword1" class="field-icon">
                                                                <i class="fas fa-lock"></i>
                                                            </label>
                                                            <input type="password" name="password" id="inputNewPassword1" data-error-threshold="{$pwStrengthErrorThreshold}" data-warning-threshold="{$pwStrengthWarningThreshold}"  placeholder="{$LANG.clientareapassword}" autocomplete="off"{if $remote_auth_prelinked} value="{$password}"{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputNewPassword2" class="field-icon">
                                                                <i class="fas fa-lock"></i>
                                                            </label>
                                                            <input type="password" name="password2" id="inputNewPassword2"  placeholder="{$LANG.clientareaconfirmpassword}" autocomplete="off"{if $remote_auth_prelinked} value="{$password}"{/if}>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-default btn-sm btn-xs-block generate-password" data-targetfields="inputNewPassword1,inputNewPassword2">
                                                                {$LANG.generatePassword.btnLabel}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="password-strength-meter">
                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="passwordStrengthMeterBar">
                                                                </div>
                                                            </div>
                                                            <p class="text-center small text-muted" id="passwordStrengthTextLabel">{$LANG.pwstrength}: {$LANG.pwstrengthenter}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                {if $securityquestions}
                                                <div class="row">
                                                    <div class="form-group col-sm-12">
                                                        <select name="securityqid" id="inputSecurityQId" >
                                                            <option value="">{$LANG.clientareasecurityquestion}</option>
                                                            {foreach $securityquestions as $question}
                                                                <option value="{$question.id}"{if $question.id eq $securityqid} selected{/if}>
                                                                    {$question.question}
                                                                </option>
                                                            {/foreach}
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group prepend-icon">
                                                            <label for="inputSecurityQAns" class="field-icon">
                                                                <i class="fas fa-lock"></i>
                                                            </label>
                                                            <input type="password" name="securityqans" id="inputSecurityQAns"  placeholder="{$LANG.clientareasecurityanswer}" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                {/if}
                                            </div>

                                            {if $showMarketingEmailOptIn}
                                                <div class="marketing-email-optin">
                                                    <h4>{lang key='emailMarketing.joinOurMailingList'}</h4>
                                                    <p>{$marketingEmailOptInMessage}</p>
                                                    <input type="checkbox" name="marketingoptin" value="1"{if $marketingEmailOptIn} checked{/if} class="no-icheck toggle-switch-success" data-size="small" data-on-text="{lang key='yes'}" data-off-text="{lang key='no'}">
                                                </div>
                                            {/if}

                                            {include file="$template/includes/captcha.tpl"}

                                            <br/>
                                            {if $accepttos}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-danger tospanel">
                                                            <div class="panel-heading">
                                                                <h3 class="panel-title"><span class="fas fa-exclamation-triangle tosicon"></h4> &nbsp; {$LANG.ordertos}</h3>
                                                            </div>
                                                            <div class="panel-body">
                                                                <div class="col-md-12">
                                                                    <input type="checkbox" name="accepttos" class="filter">
                                                                    <label class="checkbox-label c-grey">
                                                                        {$LANG.ordertosagreement} <a href="{$tosurl}" target="_blank">{$LANG.ordertos}</a>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                            <p align="center">
                                                <input class="btn btn-large btn-primary{$captcha->getButtonClass($captchaForm)}" type="submit" value="{$LANG.clientregistertitle}"/>
                                            </p>
                                        </form>
{/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/bootstrap.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/jquery.countdown.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/jquery.magnific-popup.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/slick.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/isotope.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/filter.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/swiper.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/scripts.min.js"></script>
