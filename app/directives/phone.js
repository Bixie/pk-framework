export default {

    params: ['icon',],

    update(value) {
        if (value) {
            if (this.el.href !== undefined) {
                this.el.href = `tel:${value}`;
            }
            const prepared = this.el.getAttribute('itemprop');
            if (prepared !== 'telephone') {
                if (this.modifiers.phone || this.modifiers.mobile || this.modifiers.auto) {
                    let icon = this.modifiers.mobile ? 'mobile' : 'phone';
                    if (this.modifiers.auto) {
                        icon = value.match(/\(?0\)?6/) ? 'mobile' : 'phone';
                    }
                    const iconEl = document.createElement('span');
                    iconEl.className = 'uk-icon-justify uk-icon-' + icon;
                    this.el.insertBefore(iconEl, this.el.firstChild);
                }
                this.el.setAttribute('itemprop', 'telephone');
            }
        } else {
            this.el.removeAttribute('itemprop');
        }
    },

};
