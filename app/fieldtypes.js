/*global Vue*/

import BixieFieldtypeTemplate from './templates/fieldtypes.html';
import BixieFieldtypeMixin from './mixins/fieldtype';
import FieldtypeAgree from '../fieldtypes/agree/agree.vue';
import FieldtypeCheckbox from '../fieldtypes/checkbox/checkbox.vue';
import FieldtypeDob from '../fieldtypes/dob/dob.vue';
import FieldtypeEmail from '../fieldtypes/email/email.vue';
import FieldtypeHtmlcode from '../fieldtypes/htmlcode/htmlcode.vue';
import FieldtypeMap from '../fieldtypes/map/map.vue';
import FieldtypePulldown from '../fieldtypes/pulldown/pulldown.vue';
import FieldtypeRadio from '../fieldtypes/radio/radio.vue';
import FieldtypeSitelink from '../fieldtypes/sitelink/sitelink.vue';
import FieldtypeText from '../fieldtypes/text/text.vue';
import FieldtypeTextbox from '../fieldtypes/textbox/textbox.vue';
import FieldtypeUpload from '../fieldtypes/upload/upload.vue';

const BixieFieldtypes = {

    name: 'Fieldtypes',

    components: {
        'agree': FieldtypeAgree,
        'checkbox': FieldtypeCheckbox,
        'dob': FieldtypeDob,
        'email': FieldtypeEmail,
        'htmlcode': FieldtypeHtmlcode,
        'map': FieldtypeMap,
        'pulldown': FieldtypePulldown,
        'radio': FieldtypeRadio,
        'sitelink': FieldtypeSitelink,
        'text': FieldtypeText,
        'textbox': FieldtypeTextbox,
        'upload': FieldtypeUpload,
    },

    props: {
        'fields': {type: Array, default: () => ([]),},
        'model': {type: Object, default: () => ({}),},
        'editType': {type: String, default: '',},
        'form': {type: Object, default: () => ({}),},
    },

    computed: {
        isAdmin() {
            return !!this.editType;
        },
    },

    template: BixieFieldtypeTemplate,
};
//add to vue asynchronously, so other components can register
Vue.component('fieldtypes', resolve => resolve(BixieFieldtypes));
//expose to external fields
window.BixieFieldtypes = BixieFieldtypes;
window.BixieFieldtypeMixin = BixieFieldtypeMixin;
