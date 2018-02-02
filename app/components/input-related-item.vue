<template>

    <div>
        <div v-show="selected_identifier" class="uk-badge uk-flex uk-flex-middle">
            <em class="uk-text-small uk-margin-small-right">{{ selected_identifier }}</em>
            <span class="uk-flex uk-flex-middle uk-flex-wrap uk-flex-item-1">
                <span class="uk-flex-item-1 uk-text-left uk-margin-small-right">{{ selected_name }} </span>
                <small v-if="extra_key" class="uk-margin-small-right">({{ selected_extra_key }})</small>
            </span>
            <a @click="remove" class="uk-close"></a>
        </div>

        <p class="uk-margin-bottom-remove">
            <button type="button" class="uk-button" :class="buttonClass" @click="pick">{{ buttonText | trans }}</button>
        </p>

        <v-modal v-ref:modal large>

            <table-list v-ref:table-list
                        :resource="resource"
                        :config="config"
                        :name="name"
                        :excluded="excluded_ids"
                        :label="label"
                        :extra_key="extra_key"
                        :identifier="identifier"></table-list>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-link uk-modal-close" type="button">{{ 'Cancel' | trans }}</button>
                <button class="uk-button uk-button-primary" type="button" :disabled="!hasSelection()" @click="select()">
                    {{ 'Select' | trans }}
                </button>
            </div>

        </v-modal>
    </div>


</template>

<script>
/*global _ */

export default {

    name: 'InputRelatedItem',

    props: {
        'model': {type: Number, default: 0,},
        'selected': {type: Object, default: () => ({}),},
        'excluded': {type: Array, default: () => ([]),},
        'resource': {type: String, default: '',},
        'config': {type: Object, default: () => ({filter: {search: '', order: 'title asc',},}),},
        'name': {type: String, default: 'items',},
        'identifier': {type: String, default: 'id',},
        'label': {type: String, default: 'title',},
        'buttonClass': {type: String, default: 'uk-button-small',},
        'buttonText': {type: String, default: 'Please select',},
        'extra_key': {type: String, default: 'slug',},
        'onSelect': {type: Function, default: _.noop,},
        'onRemove': {type: Function, default: _.noop,},
    },

    computed: {
        selected_identifier() {
            return this.selected ? this.selected[this.identifier] : '';
        },
        selected_name() {
            return this.selected ? this.selected[this.label] : '';
        },
        selected_extra_key() {
            return this.selected ? this.selected[this.extra_key] : '';
        },
        excluded_ids() {
            return _.merge(this.excluded, [this.selected_identifier,]);
        },
    },

    methods: {

        pick() {
            this.$refs.modal.open();
        },

        select() {
            this.selected = _.first(this.$refs.tableList.getSelected());
            this.model = this.selected[this.identifier];
            this.onSelect(this.selected);
            this.$refs.modal.close();
        },

        remove() {
            this.selected = {};
            this.model = '';
            this.onRemove();
        },

        hasSelection() {
            return this.$refs.tableList.nrSelected() === 1;
        },

        isSelected(item) {
            return this.selected === item;
        },

    },

};

</script>
