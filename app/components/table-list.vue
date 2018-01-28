<template>

    <div>
        <div class="uk-form uk-form-icon">
            <i class="uk-icon-search"></i>
            <input type="search" v-model="config.filter.search" :placeholder="$trans('Search')" debounce="300">
        </div>

        <div class="uk-margin uk-overflow-container">
            <table class="uk-table uk-table-hover uk-table-middle uk-form">
                <thead>
                <tr>
                    <th class="pk-table-width-minimum"><input type="checkbox" v-check-all:selected.literal="input[name=id]"></th>
                    <th class="pk-table-min-width-100">{{ 'ID' | trans }}</th>
                    <th class="pk-table-min-width-200">{{ 'Name' | trans }}</th>
                    <th v-if="extra_key" class="pk-table-min-width-100">{{ 'Extra' | trans }}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="check-item" v-for="item in items" :class="{'uk-active': active(item)}">
                    <td><input type="checkbox" name="id" :value="getIdentifier(item)" :disabled="disabled(item)"></td>
                    <td>{{ getIdentifier(item) }}</td>
                    <td>
                        <span v-if="disabled(item)" class="uk-text-muted">{{ getLabel(item) }}</span>
                        <a v-else @click="select(item)">{{ getLabel(item) }}</a>
                    </td>
                    <td v-if="extra_key">{{ getExtraKey(item) }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <h3  v-show="items && !items.length"class="uk-text-muted uk-text-center">{{ 'No items found.' | trans }}</h3>

        <v-pagination :page.sync="config.page" :pages="pages" v-show="pages > 1" :replace-history="false"></v-pagination>

    </div>

</template>

<script>

    export default {

        name: 'TableList',

        props: {
            'config': {type: Object, default: () => ({filter: {search: '', order: 'title asc'}, page: 0,}),},
            'resource': {type: String, default: '',},
            'excluded': {type: Array, default: () => ([]),},
            'identifier': {type: String, default: 'id',},
            'name': {type: String, default: 'items',},
            'extra_key': {type: String, default: 'slug',},
            'limit': {type: Number, default: 10,},
            'label': {type: String, default: 'title',},
        },

        data: () => ({
            items: false,
            pages: 0,
            count: '',
            selected: [],
        }),

        watch: {
            'config.filter': {
                handler: function () {
                    if (this.config.page) {
                        this.config.page = 0;
                    } else {
                        this.load();
                    }
                },
                deep: true,
            },
        },

        created() {
            this.Resource = this.$resource(this.resource);
            this.config.filter.limit = this.limit;
            this.$watch('config.page', this.load, {immediate: true,});
        },

        methods: {
            active(item) {
                return this.selected.indexOf(String(item[this.identifier])) !== -1;
            },

            disabled(item) {
                return this.excluded.indexOf(item[this.identifier]) !== -1;
            },

            load() {
                this.Resource.query(this.config).then(res => {
                    const data = res.data;

                    this.$set('items', this.name ? data[this.name] : data);
                    this.$set('pages', data.pages);
                    this.$set('count', data.count);
                    this.$set('selected', []);
                }, () => this.$notify('Loading failed.', 'danger'));
            },

            select(item) {
                const identifier = String(item[this.identifier]);
                if (this.selected.indexOf(identifier) === -1) {
                    this.selected.push(identifier)
                }
            },

            getLabel(item) {
                return item[this.label] || '';
            },

            getIdentifier(item) {
                return String(item[this.identifier]) || '';
            },

            getExtraKey(item) {
                if (!this.extra_key) {
                    return '';
                }
                return item[this.extra_key] || '';
            },

            nrSelected() {
                return this.selected.length;
            },

            getSelected() {
                return this.items.filter(item => this.selected.indexOf(String(item[this.identifier])) !== -1)
            },

        },
    };

</script>
