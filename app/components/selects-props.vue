<template>

    <div class="uk-form">

        <div v-for="prop in props" v-bind="attrs">

            <label class="uk-form-label">{{ prop.label }}</label>

            <div class="uk-form-controls">
                <select v-model="selected[prop.name]" v-bind="inputAttrs" @change="setValue(prop)">
                    <option v-for="option in prop.options" :value="option.value">{{ option.text }}</option>
                </select>
            </div>

        </div>

    </div>

</template>

<script>
/*global _ */

import md5 from 'js-md5';

export default {

    name: 'SelectProps',

    props: {
        props: [Array, Object,],
        values: [Array, Object,],
        hash: {type: String, default: '',},
        attrs: {type: Object, default: () => ({}),},
        inputAttrs: {type: Object, default: () => ({class: 'uk-form-width-medium',}),},
    },

    data: () => ({
        selected: {},
    }),

    created() {
        this.init();
        this.$on('addtocart.init', this.init);
    },

    methods: {
        init() {
            const values = this.values;
            const selected = this.selected;
            if (!_.isArray(this.props)) {
                this.props = [];
            }
            this.values = {};
            this.selected = {};
            this.props.forEach(prop => {
                if (!_.find(prop.options, {value: selected[prop.name],})) {
                    this.values[prop.name] = {}; //get new default
                }
                this.values[prop.name] = _.defaults((values[prop.name] || {}), {
                    option: (_.first(prop.options) || {value: '',}),
                    prop,
                });
                this.selected[prop.name] = this.values[prop.name].option.value;
            });
            this.setHash();
        },
        setValue(prop) {
            const value = this.selected[prop.name];
            this.values[prop.name].option = _.find(prop.options, {value,});
            this.setHash();
        },
        setHash() {
            this.hash = md5(JSON.stringify(this.selected));
        },
    },

};

</script>
