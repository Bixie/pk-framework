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

    Vue.component('fieldtypes', function (resolve) {
        resolve(module.exports);
    });

</script>

