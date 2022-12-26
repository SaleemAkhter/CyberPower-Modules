<ul class="nav nav--h nav--tabs" data-icon-sets>
    <li class="nav__item is-active">
        <a class="nav__link" data-toggle="lu-tab" href="#{$type}-lagom-small">
            <span>{$lang.menu.menu_items.modal.icon.ls}</span>
        </a>
    </li>
    <li class="nav__item">
        <a class="nav__link" data-toggle="lu-tab" href="#{$type}-lagom-medium">
            <span>{$lang.menu.menu_items.modal.icon.lm}</span>
        </a>
    </li>
    <li class="nav__item ">
        <a class="nav__link" data-toggle="lu-tab" href="#{$type}-font-awesome">
            <span>{$lang.menu.menu_items.modal.icon.fa}</span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane is-active" id="{$type}-lagom-small">
        <div class="media" data-media-container>
            <div class="media__search input-group">
                <i class="input-group__addon lm lm-search"></i>
                <input type="text" class="form-control" placeholder="Search" data-media-search>
            </div>
           <div class="media__list" data-media-list>

            </div>
        </div>
    </div>
    <div class="tab-pane" id="{$type}-lagom-medium">
        <div class="media" data-media-container>
            <div class="media__search input-group">
                <i class="input-group__addon lm lm-search"></i>
                <input type="text" class="form-control" placeholder="Search" data-media-search>
            </div>
            <div class="media__list" data-media-list>

            </div>
        </div>
    </div>
    <div class="tab-pane" id="{$type}-font-awesome">
        <div class="media" data-media-container>
            <div class="media__search input-group">
                <i class="input-group__addon lm lm-search"></i>
                <input type="text" class="form-control" placeholder="Search" data-media-search>
            </div>
            <div class="media__list" data-media-list>

            </div>
        </div>
    </div>
</div>