<template>

    <div>

        <div @click="pick">
            <ul class="uk-float-right uk-subnav pk-subnav-icon">
                <li><a class="pk-icon-delete pk-icon-hover" :title="'Delete' | trans"
                       data-uk-tooltip="{delay: 500, 'pos': 'left'}" @click="remove"></a></li>
            </ul>
            <a class="pk-icon-folder-circle uk-margin-right"></a>
            <a v-if="!folder" class="uk-text-muted">{{ 'Select folder' | trans }}</a>
            <a v-else>{{ folder }}</a>
        </div>

        <v-modal v-ref:modal large>

            <panel-finder :root="storage" v-ref:finder modal></panel-finder>

            <div class="uk-modal-footer uk-text-right">
                <button class="uk-button uk-button-link uk-modal-close" type="button">{{ 'Cancel' | trans }}</button>
                <button class="uk-button uk-button-primary" type="button" :disabled="!hasSelection()" @click="select()">{{ 'Select' | trans }}</button>
            </div>

        </v-modal>

    </div>

</template>

<script>
/*global _ */

export default {

    name: 'InputFolder',

    props: {
        'folder': {type: String, default: '',},
    },

    data: () => _.merge({}, window.$pagekit),

    methods: {

        pick() {
            this.$refs.modal.open();
        },

        select() {
            this.folder = this.$refs.finder.getSelected()[0];
            this.$dispatch('folder-selected', this.folder);
            this.$refs.finder.removeSelection();
            this.$refs.modal.close();
        },

        remove(e) {
            e.stopPropagation();
            this.folder = ''
        },

        hasSelection() {
            const selected = this.$refs.finder.getSelected();
            return selected.length === 1 && !selected[0].match(/\.(.+)$/i);
        },

    },

};

</script>
