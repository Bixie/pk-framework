<template>

    <div class="uk-form-row">

        <v-editor :value.sync="field.data.value[0]" :options="{markdown : field.data.markdown}"></v-editor>
        <p>
            <label><input type="checkbox" v-model="field.data.markdown"> {{ 'Enable Markdown' | trans }}</label>
        </p>

    </div>

</template>

<script>

    export default {

        created() {
            this.field.data = _.merge({
                'markdown': false
            }, this.field.data);
        },

    };

</script>
