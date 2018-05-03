<template>
    <input type="text" v-model="search_text" :placeholder="placeholder" @keyup.enter.stop="search"/>
</template>
<script>
/*global _, UIkit, Vue */

export default {

    name: 'InputTypeahead',

    props: {
        search_text: String,
        placeholder: {type: String, default: '',},
        datasets: Object,
        onSelect: {type: Function, default: _.noop,},
        onActive: {type: Function, default: _.noop,},
        onSearch: {type: Function, default: _.noop,},
    },

    data: () => ({
        $input: null,
    }),

    methods: {
        initDatasets(datasets_data) {
            const pr = [];
            _.forEach(this.datasets, dataset => {
                const {local, remote, prefetch, } = datasets_data[dataset.name];
                pr.push(dataset.init(local, remote, prefetch));
            });
            Vue.Promise.all(pr).then(datasets => {
                this.$input = UIkit.$(this.$el).typeahead({
                    minLength: 0,
                    highlight: true,
                },
                datasets
                ).bind('typeahead:select', this.onSelectTypeahead)
                    .bind('typeahead:active', this.onActive)
                    .bind('typeahead:autocomplete', this.onSelectTypeahead);
                this.$dispatch('typeahead.ready', this);
            }, e => this.$notify('Error in loading typeahead: ' + e, 'danger'));

        },
        search() {
            this.$input.typeahead('close');
            this.onSearch();
        },
        onSelectTypeahead(e, suggestion) {
            //blur element to trigger change event
            this.$el.blur();
            if (suggestion.type && this.datasets[suggestion.type]) {
                this.datasets[suggestion.type].onSelect(suggestion)
                    .then((suggestion) => this.onSelect(suggestion));
            } else {
                this.onSelect(suggestion)
            }
        },
    },
};
</script>