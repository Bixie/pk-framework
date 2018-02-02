<template>

    <div>
        <div class="uk-grid">
            <div v-for="item in selected" class="uk-width-1-1">
                <div class="uk-badge uk-flex uk-flex-middle uk-margin-small-bottom"
                     track-by="$index">
                    <em class="uk-text-small uk-margin-small-right">{{ getIdentifier(item) }}</em>
                    <div class="uk-flex uk-flex-middle uk-flex-wrap uk-flex-item-1">
                        <span class="uk-flex-item-1 uk-text-left">{{ getLabel(item) }} </span>
                        <small v-if="extra_key" class="uk-margin-small-left">({{ getExtraKey(item) }})</small>
                    </div>
                    <a @click="remove(item)" class="uk-close uk-margin-small-left"></a>
                </div>
            </div>
        </div>

        <p class="uk-margin-bottom-remove">
            <button type="button" class="uk-button uk-button-small" @click="pick">{{ 'Please select' | trans }}</button>
        </p>

        <v-modal v-ref:modal large>

            <table-list v-ref:table-list
                        :resource="resource"
                        :config="config"
                        :excluded="excluded_ids"
                        :name="name"
                        :extra_key="extra_key"
                        :label="label"
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

    name: 'InputRelatedItems',

    props: {
        'model': {type: Array, default: () => ([]),},
        'selected': {type: Array, default: () => ({}),},
        'excluded': {type: Array, default: () => ([]),},
        'resource': {type: String, default: '',},
        'config': {type: Object, default: () => ({filter: {search: '', order: 'title asc',},}),},
        'name': {type: String, default: 'items',},
        'identifier': {type: String, default: 'id',},
        'label': {type: String, default: 'title',},
        'extra_key': {type: String, default: 'slug',},
        'onSelect': {type: Function, default: _.noop,},
        'onRemove': {type: Function, default: _.noop,},
    },

    computed: {
        excluded_ids() {
            return _.merge(this.excluded, this.model);
        },
    },

    watch: {
        selected(items) {
            this.model = _.map(items, item => this.getIdentifier(item));
        },
    },

    methods: {

        pick() {
            this.$refs.modal.open();
        },

        select() {
            const selected = _.filter(this.$refs.tableList.getSelected(), item => {
                return _.find(this.selected, this.identifier, this.getIdentifier(item)) === undefined;
            });
            this.selected = this.selected.concat(selected);
            this.onSelect(selected);
            this.$refs.modal.close();
        },

        remove(item) {
            this.selected.$remove(item);
            this.onRemove(item);
        },

        getIdentifier(item) {
            return item[this.identifier] || ''; //todo Vue warn?
        },

        getLabel(item) {
            return item[this.label] || '';
        },

        getExtraKey(item) {
            if (!this.extra_key) {return '';}
            return item[this.extra_key] || '';
        },

        hasSelection() {
            return this.$refs.tableList.nrSelected() > 0;
        },

        isSelected(item) {
            return this.selected === item;
        },

    },

};

</script>