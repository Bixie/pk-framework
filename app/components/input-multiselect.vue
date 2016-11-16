<template>

    <ul class="uk-nav uk-nav-side">
        <li v-for="value in selectOptions" @click="toggle(value)"
            :class="{'uk-active': selected(value)}">
            <a href="#" class="uk-flex uk-flex-middle uk-flex-space-between">
                 <span>{{ $key }}</span>
                 <i class="uk-float-right"
                   :class="{'uk-icon-check': selected(value), 'uk-icon-ban': !selected(value)}"></i>
           </a>
        </li>
    </ul>


</template>

<script>

    module.exports = {

        props: {
            'value': [Array, Object],
            'options': Array
        },

        computed: {
            selectOptions() {
                if (_.isArray(this.options)) {
                    var options = {};
                    this.options.forEach(option => {
                        options[option.text] = option.value
                    });
                    return options;
                }
                return this.options;
            }
        },

        methods: {

            toggle(option) {
                if (this.selected(option)) {
                    this.value.$remove(option);
                } else {
                    this.value.push(option);
                }
            },

            selected(tag) {
                return this.value.indexOf(tag) > -1;
            }

        }

    };

</script>
