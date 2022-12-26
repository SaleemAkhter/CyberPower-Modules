<ul class="lu-nav lu-nav--sub">
    {foreach from=$button->getButtons() key=subButtonKey item=subButton}
        <li class="lu-nav__item">
            {$subButton->getHtml()}
        </li>
    {/foreach}
</ul>