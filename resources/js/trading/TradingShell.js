/**
 * Owns the chrome shared by every page that extends layouts.desktop.trading:
 * the left icon-rail flyouts and the top-bar balance/avatar menus. Boots on
 * every such page (not just the dashboard), since those elements are part of
 * the shared layout, not the dashboard-specific content.
 */
export default class TradingShell {
    constructor() {
        this.state = { activeNav: null, balanceMenuOpen: false, avatarMenuOpen: false };
        this.navOverrides = {};

        this._cacheDom();
        this._bindEvents();
    }

    _cacheDom() {
        this.el = {
            navButtons: document.querySelectorAll('.rail-nav-btn'),
            navFlyout: document.getElementById('navFlyout'),
            navFlyoutClose: document.getElementById('navFlyoutClose'),
            flyoutSections: document.querySelectorAll('.flyout-section'),

            balanceMenuBtn: document.getElementById('balanceMenuBtn'),
            balanceMenu: document.getElementById('balanceMenu'),
            avatarMenuBtn: document.getElementById('avatarMenuBtn'),
            avatarMenu: document.getElementById('avatarMenu'),
        };
    }

    _bindEvents() {
        this.el.navButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._onNav(btn.dataset.nav));
        });
        this.el.navFlyoutClose?.addEventListener('click', () => this._closeFlyout());

        this.el.balanceMenuBtn?.addEventListener('click', () => this._toggleBalanceMenu());
        this.el.avatarMenuBtn?.addEventListener('click', () => this._toggleAvatarMenu());

        document.addEventListener('click', (e) => this._onOutsideClick(e));
    }

    /** Let a page-specific script (e.g. the dashboard) override what a nav key does — e.g. "market" opens the asset popover instead of a flyout. */
    registerNavOverride(key, handler) {
        this.navOverrides[key] = handler;
    }

    _onNav(key) {
        if (this.navOverrides[key]) {
            this.navOverrides[key]();
            return;
        }

        const hasFlyout = Array.from(this.el.flyoutSections).some((s) => s.dataset.flyoutFor === key);
        if (!hasFlyout) return; // real <a> with no flyout — let it navigate natively

        this.state.activeNav = this.state.activeNav === key ? null : key;
        this._renderFlyout();
    }

    _closeFlyout() {
        this.state.activeNav = null;
        this._renderFlyout();
    }

    _renderFlyout() {
        const open = !!this.state.activeNav;
        this.el.navFlyout?.classList.toggle('hidden', !open);
        this.el.flyoutSections.forEach((section) => {
            section.classList.toggle('hidden', section.dataset.flyoutFor !== this.state.activeNav);
        });
        this.el.navButtons.forEach((btn) => {
            btn.classList.toggle('rail-nav-btn--active', btn.dataset.nav === this.state.activeNav);
        });
    }

    _toggleBalanceMenu() {
        this.state.balanceMenuOpen = !this.state.balanceMenuOpen;
        this.state.avatarMenuOpen = false;
        this._renderTopbarMenus();
    }

    _toggleAvatarMenu() {
        this.state.avatarMenuOpen = !this.state.avatarMenuOpen;
        this.state.balanceMenuOpen = false;
        this._renderTopbarMenus();
    }

    _renderTopbarMenus() {
        this.el.balanceMenu?.classList.toggle('hidden', !this.state.balanceMenuOpen);
        this.el.avatarMenu?.classList.toggle('hidden', !this.state.avatarMenuOpen);
    }

    _onOutsideClick(e) {
        if (this.state.balanceMenuOpen && this.el.balanceMenu && !this.el.balanceMenu.contains(e.target) && !this.el.balanceMenuBtn?.contains(e.target)) {
            this.state.balanceMenuOpen = false;
            this.el.balanceMenu.classList.add('hidden');
        }
        if (this.state.avatarMenuOpen && this.el.avatarMenu && !this.el.avatarMenu.contains(e.target) && !this.el.avatarMenuBtn?.contains(e.target)) {
            this.state.avatarMenuOpen = false;
            this.el.avatarMenu.classList.add('hidden');
        }
        if (this.state.activeNav && this.el.navFlyout && !this.el.navFlyout.contains(e.target) && !Array.from(this.el.navButtons).some((b) => b.contains(e.target))) {
            this._closeFlyout();
        }
    }
}
