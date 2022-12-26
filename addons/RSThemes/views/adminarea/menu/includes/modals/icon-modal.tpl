<div class="modal" id="menuIconModal" data-modal-menu-icon>
    <div class="modal__dialog">
        <div class="modal__content">
            <div class="modal__top top">
                <h6 class="top__title">{$lang.menu.menu_items.modal.icon.title}</h6>
                <div class="top__toolbar">
                    <button class="close btn btn--xs btn--icon btn--link cancel__item" data-dismiss="lu-modal" aria-label="Close">
                        <i class="btn__icon lm lm-close"></i>
                    </button>
                </div>
            </div>
            <div class="modal__body p-b-0x" data-modal-menu-icon-body>
                {include file="adminarea/menu/includes/components/icon-tabs.tpl" type='menu-icon'}
            </div>
            <div class="modal__actions">
                <button class="btn btn--primary" type="button" data-modal-menu-icon-submit data-index data-parent>
                    <span class="btn__text">{$lang.general.confirm}</span>
                </button>
                <a class="btn btn--default btn--outline cancel__item" data-dismiss="lu-modal">
                    <span class="btn__text">{$lang.general.cancel}</span>
                </a>
            </div>
        </div>
    </div>
</div>