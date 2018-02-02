<template>

    <div :class="classes(['uk-form-row', (isAdmin ? 'uk-hidden' : '')], field.data.classSfx)"
         v-html="html">
    </div>

</template>

<script>
/*global _ */

import BixieFieldtypeMixin from '../../app/mixins/fieldtype';
import FieldtypeHtmlcodeSettings from './components/settings.vue';

export default {

    name: 'FieldtypeHtmlcode',

    mixins: [BixieFieldtypeMixin,],

    settings: FieldtypeHtmlcodeSettings,

    appearance: {},

    data: () => ({
        fieldid: _.uniqueId('bixiefieldtype_'),
    }),

    computed: {
        html() {
            return this.fieldValue.formatted ? this.fieldValue.formatted[0] : '';
        },
    },

};

</script>
