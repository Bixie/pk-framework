<template>

    <div>
        <component v-for="field in fields | orderBy 'priority'"
                   :is="field.type"
                   :field="field"
                   :model="model"
                   :is-admin="isAdmin"
                   :form="form"></component>
    </div>

</template>

<script>

    window.BixieFieldtypeMixin = require('./mixins/fieldtype');
    window.BixieFieldtypes = module.exports = {

        props: {
            'fields': {type: Array, default: function () { return [];}},
            'model': {type: Object, default: function () { return {};}},
            'editType': {type: String, default: ''},
            'form': {default: function () { return {};}}
        },

        computed: {
            isAdmin: function () {
                return !!this.editType
            }
        },

        components: {}

    };

    require(`../fieldtypes/agree/agree.vue`);
    require(`../fieldtypes/checkbox/checkbox.vue`);
    require(`../fieldtypes/dob/dob.vue`);
    require(`../fieldtypes/email/email.vue`);
    require(`../fieldtypes/htmlcode/htmlcode.vue`);
    require(`../fieldtypes/pulldown/pulldown.vue`);
    require(`../fieldtypes/radio/radio.vue`);
    require(`../fieldtypes/sitelink/sitelink.vue`);
    require(`../fieldtypes/text/text.vue`);
    require(`../fieldtypes/textbox/textbox.vue`);
    require(`../fieldtypes/upload/upload.vue`);

    Vue.component('fieldtypes', function (resolve) {
        resolve(module.exports);
    });

</script>

