<template>

    <div :class="classes(['uk-form-row'], field.data.classSfx)">
        <label :for="fieldid" class="uk-form-label" v-show="!field.data.hide_label">{{ fieldLabel | trans }}
            <a v-if="field.data.help_text && field.data.help_show == 'tooltip_icon'"
               class="uk-icon-info uk-icon-hover uk-margin-small-top uk-float-right"
               :title="field.data.help_text" data-uk-tooltip="{delay: 100}"></a>
        </label>

        <div class="uk-form-controls">
            <input type="text" class="uk-form-width-large" placeholder="{{ field.data.placeholder || '' | trans }}"
                   :name="fieldid" :id="fieldid"
                   v-model="inputValue"
                   v-validate:required="fieldRequired">

            <p v-if="field.data.help_text && field.data.help_show == 'block'"
               class="uk-form-help-block">{{{field.data.help_text}}}</p>

            <p class="uk-form-help-block uk-text-danger" v-show="fieldInvalid(form)">{{ fieldRequiredMessage }}</p>
        </div>
    </div>

</template>

<script>
    import BixieFieldtypeMixin from '../../app/mixins/fieldtype';

    export default {

        name: 'FieldtypeText',

        mixins: [BixieFieldtypeMixin,],

        settings: {
            'placeholder': {
                type: 'text',
                label: 'Placeholder',
                attrs: {'class': 'uk-form-width-large',},
            },
        },

        appearance: {},

        data: () => ({
            fieldid: _.uniqueId('bixiefieldtype_'),
        }),

    };

</script>
