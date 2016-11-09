<template>

    <div :class="classes(['uk-form-row', (isAdmin ? 'uk-hidden' : '')], field.data.classSfx)">

        {{{ fieldValue.formatted[0] }}}

    </div>

</template>

<script>

    module.exports = {

        mixins: [BixieFieldtypeMixin],

        settings: require('./components/settings.vue'),

        appearance: {},

        data: function () {
            return {
                fieldid: _.uniqueId('formmakerfield_')
            };
        }

    };

    window.BixieFieldtypes.components['htmlcode'] = module.exports;

</script>
