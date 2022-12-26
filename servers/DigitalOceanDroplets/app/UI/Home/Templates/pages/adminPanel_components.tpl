{literal}
<template id="mg-admin-buttons-template{/literal}{$elementId}{literal}">
    <div v-show="buttonList">
        <div class="h4 lu-m-b-3x lu-m-t-3x">{/literal}{$MGLANG->absoluteT('serverCA','home','additionalOptions')}{literal}</div>
        <div class="lu-tiles lu-row lu-row--eq-height" style="min-height: 50px;">
            <div v-for="(adbutton, key) in buttonList" class="lu-col-sm-20p" style="justify-content: center; height: 138px;"  v-show="!sectionLoading">
                <a v-bind:class="'lu-tile lu-tile--btn ' + adbutton.class" 
                    @click="loadModal($event,key)"
                    v-bind:href="adbutton.htmlAtributes.href"
                    v-bind:data-toggle="adbutton.dataToggle"
                    v-bind:data-title="adbutton.buttonTitle">
                    <div class="lu-i-c-6x">
                        <img v-bind:src="adbutton.image"  v-bind:alt="adbutton.iconTitle" />
                    </div>
                    <div class="lu-tile__title">{{ adbutton.iconTitle }}</div>
                </a>
            </div>
            <div class="lu-preloader-container lu-preloader-container--overlay" v-show="sectionLoading">
                <div class="lu-preloader lu-preloader--sm"></div>
            </div>           
        </div>
    </div>
</template>
{/literal}
