<div class="logincontainer{if $linkableProviders} with-social{/if}">
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
                <li class="btn btn-secondary active mb-2">Already Registered?</li>
                <li id="regBu" class="btn btn-secondary">Create a New Account</li>
              </ul>
            </div>
                {include file="$template/includes/flashmessage.tpl"}
                <div class="{if !$linkableProviders}hidden{/if}">
                        {include file="$template/includes/linkedaccounts.tpl" linkContext="login" customFeedback=true}
                </div>
                <div class="providerLinkingFeedback mt-5"></div>
            <div class="row">
              <div class="col-sm-12">
                <div class="table tabs-item active">
                  <div class="cd-filter-block mb-0">
                      <form action="{routePath('login-validate')}" method="post" role="form" class="comments-form">
                        <div class="row">
                          <div class="col-md-6 position-relative">
                            <label><i class="fas fa-envelope"></i></label>
                            <input type="email" name="username" id="inputEmail" placeholder="{$LANG.enteremail}" autofocus>
                          </div>
                          <div class="col-md-6 position-relative">
                            <label><i class="fas fa-lock"></i></label>
                            <input type="password" name="password" id="inputPassword" placeholder="{$LANG.clientareapassword}" autocomplete="off">
                          </div>
                          <div class="col-md-12 mt-5 position-relative">
                            <button type="submit" value="{$LANG.loginbutton}" id="login" class="btn btn-default-yellow-fill mt-0 mb-3 me-3">Login <i class="fas fa-lock"></i>
                            </button>
                            <a class="golink me-3 position-relative" href="{routePath('password-reset-begin')}">{$LANG.forgotpw}</a>
                            <ul class="list d-inline">
                              <li>
                                <input name="rememberme" type="checkbox" class="filter">
                                <label for="checkbox" class="checkbox-label c-grey" >{$LANG.loginrememberme}</label>
                              </li>
                            </ul>
                          </div>
                        {if $captcha->isEnabled()}
                            <div class="text-center margin-bottom">
                              {if $captcha->isEnabled() && $captcha->isEnabledForForm($captchaForm)}
                                <div class="text-center{if $containerClass}{$containerClass}{else} row{/if}">
                                    {if $templatefile == 'homepage'}
                                        <div class="domainchecker-homepage-captcha">
                                    {/if}

                                    {if $captcha == "recaptcha"}
                                        <div id="google-recaptcha-domainchecker" class="form-group recaptcha-container"></div>
                                    {elseif !in_array($captcha, ['invisible', 'recaptcha'])}
                                        <div class="col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
                                            <div id="default-captcha-domainchecker" class="{if $filename == 'domainchecker'}input-group input-group-box {/if}text-center">
                                                <p>{lang key="captchaverify"}</p>

                                                <div class="col-xs-6 captchaimage">
                                                    <img id="inputCaptchaImage" data-src="{$systemurl}includes/verifyimage.php" src="{$systemurl}includes/verifyimage.php" align="middle" />
                                                </div>

                                                <div class="col-xs-6">
                                                    <input id="inputCaptcha" type="text" name="code" maxlength="6" class="form-control {if $filename == 'register'}pull-left{/if}"
                                                          data-toggle="tooltip" data-placement="right" data-trigger="manual" title="{lang key='orderForm.required'}"/>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                    {if $templatefile == 'homepage'}
                                        </div>
                                    {/if}
                                </div>
                            {/if}
   
                            </div>
                        {/if}
                        </div>
                      </form>
                  </div>
                </div>            
              </div>
            </div>
          </div>
        </section>
      </div>

    <div class="row">
        <div class="col-sm-{if $linkableProviders}7{else}12{/if}">
        </div>
        <div class="col-sm-5{if !$linkableProviders} hidden{/if}">
            {include file="$template/includes/linkedaccounts.tpl" linkContext="login" customFeedback=true}
        </div>
    </div>
</div>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/bootstrap.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/jquery.countdown.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/jquery.magnific-popup.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/slick.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/isotope.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/filter.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/swiper.min.js"></script>
<script defer src="{$WEB_ROOT}/templates/{$template}/assets/antler/js/scripts.min.js"></script>
