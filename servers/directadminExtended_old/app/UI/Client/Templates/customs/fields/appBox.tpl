<div class="lu-col-xs-6 lu-col-md-20p" style="margin-right:12px;margin-left:-12px;cursor:pointer;">
    <a class="lu-tile lu-tile--btn" @click="loadModal($event, '{$rawObject->getId()}' , '{$rawObject->getNamespace()}', ['{$rawObject->getSid()}', '{$rawObject->getVersion()}', '{$rawObject->getAppName()}'], null, true)">
        <div class="lu-i-c-6x">
            <img
                    src="{$rawObject->getImage()}" alt="">
        </div>
        <div class="lu-tile__title">{$rawObject->getAppName()} {$rawObject->getVersion()}</div>
    </a>
</div>
