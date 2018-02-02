<template>

    <div :class="classes(['uk-form-row', (isAdmin ? 'uk-hidden' : '')], field.data.classSfx)">
        <label :for="fieldid" class="uk-form-label" v-show="!field.data.hide_label">{{ fieldLabel | trans }}
            <a v-if="field.data.help_text && field.data.help_show == 'tooltip_icon'"
               class="uk-icon-info uk-icon-hover uk-margin-small-top uk-float-right"
               :title="field.data.help_text" data-uk-tooltip="{delay: 100}"></a>
        </label>

        <div class="uk-form-controls">

            <div v-if="message.message" class="uk-alert" :class="message.msg_class">{{ message.message }}</div>

            <ul class="uk-list uk-list-striped" v-if="fieldValue.value.length">
                <li v-for="file in valuedata" class="uk-flex uk-flex-middle">
                    <div class="uk-flex-item-1 uk-margin-left">

                        <a class="uk-icon-hover uk-icon-trash-o uk-float-right uk-margin-small-top uk-margin-small-right"
                           @click="removeValue(file.value)" :title="'Remove file' | trans"></a>

                        <h4 class="uk-margin-remove">
                            <a :href="$url(file.url)" download><i class="uk-icon-file-o uk-margin-small-right"></i>{{ file.name }}</a>
                        </h4>
                        <small>{{ file.size | fileSize }}</small>
                    </div>
                    <div v-if="isImage(file.url)" class="uk-margin-left">
                        <img :src="$url(file.url)" :alt="file.name" style="max-height: 100px"/>
                    </div>
                </li>
            </ul>

            <div v-show="allowedUploads" class="uk-placeholder">
                <i class="uk-icon-cloud-upload uk-margin-small-right"></i>
                {{ 'Please drop a file here or ' | trans }}<a class="uk-form-file">{{ 'select a file' | trans }}<input
                type="file" name="files[]" multiple="multiple"></a>.
            </div>

            <div class="uk-progress uk-progress-mini uk-margin-remove" v-show="upload.running">
                <div class="uk-progress-bar" :style="{width: upload.progress + '%'}"></div>
            </div>

            <p v-if="field.data.help_text && field.data.help_show === 'block'"
               class="uk-form-help-block">{{{field.data.help_text}}}</p>

        </div>

    </div>

</template>

<script>
/*global _, UIkit */

import BixieFieldtypeMixin from '../../app/mixins/fieldtype';

export default {

    name: 'FieldtypeUpload',

    filters: {
        fileSize: function (size) {
            const i = Math.floor( Math.log(size) / Math.log(1024) );
            return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB',][i];
        },
    },

    mixins: [BixieFieldtypeMixin,],

    settings: {
        'path': {
            type: 'text',
            label: 'Upload folder',
            tip: 'Folder will be a subfolder of Pagekit storage',
            attrs: {'class': 'uk-form-width-large',},
        },
        'text1': {
            type: 'paragraph',
            text: 'To enable uploads, make sure to allow uploading in the usergroups permissions (section bixie/pk-framework)',
            attrs: {'class': 'uk-text-small',},
        },
        'allowed': {
            type: 'tags',
            label: 'Allowed extensions',
            options: ['png', 'jpg', 'jpeg', 'gif', 'svg', 'csv', 'xlsx', 'pdf', 'zip', 'gz',],
            attrs: {'class': 'uk-form-width-large',},
        },
        'max_size': {
            type: 'number',
            label: 'Max file size (Mb)',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'min': 0,},
        },
        'max_files': {
            type: 'number',
            label: 'Max files (0 is unlimited)',
            attrs: {'class': 'uk-form-width-medium uk-text-right', 'min': 0,},
        },
    },

    appearance: {},

    data: () => ({
        fieldid: _.uniqueId('bixiefieldtype_'),
        action: window.$fieldtypes.ajax_url,
        path: 'uploads',
        upload: {},
        selected: [],
        message: {
            message: '',
            msg_class: '',
        },
    }),

    computed: {
        allowedUploads() {
            if (this.field.data.max_files >= 1) {
                return this.field.data.max_files - this.fieldValue.value.length;
            }
            return true;
        },
    },

    events: {
        'hook:ready'() {

            const uploader = this;
            const settings = {

                action: this.$url.route(uploader.action),

                single: false,

                allow: '*.(' + uploader.field.data.allowed.join('|') + ')',

                notallowed() {
                    uploader.setMessage(uploader.$trans('File extension not allowed.'));
                },

                before(options, files) {

                    if (uploader.allowedUploads !== true && uploader.allowedUploads < files.length) {
                        uploader.setMessage(uploader.$trans('Maximum number of files reached.'));
                        return false;
                    }

                    if (uploader.field.data.max_size > 0) {
                        if (_.filter(files, function (file) {
                            return file.size > (uploader.field.data.max_size * 1024 * 1024);
                        }).length) {
                            uploader.setMessage(uploader.$trans('File is too large.'));
                            return false;
                        }
                    }

                    _.assign(options.params, {
                        field_id: uploader.field.id,
                        action: 'uploadAction',
                        path: uploader.path,
                        _csrf: window.$pagekit.csrf,
                    });
                },

                loadstart() {
                    uploader.clearMessage();
                    uploader.$set('upload.running', true);
                    uploader.$set('upload.progress', 0);
                },

                progress(percent) {
                    uploader.$set('upload.progress', Math.ceil(percent));
                },

                allcomplete(response) {

                    const data = JSON.parse(response);

                    uploader.setMessage(data.message, data.error ? 'danger' : 'success');

                    if (data.files) {
                        data.files.forEach(file => uploader.addValue(file.name, file));
                        uploader.$dispatch('upload.success');
                    }

                    uploader.$set('upload.progress', 100);

                    setTimeout(() => uploader.$set('upload.running', false), 1500);
                },

            };

            UIkit.uploadSelect(this.$el.querySelector('.uk-form-file > input'), settings);
            UIkit.uploadDrop(this.$el.querySelector('.uk-placeholder'), settings);
        },

    },

    created() {
        if (this.field.data.path) {
            this.$set('path', this.field.data.path);
        }
    },

    methods: {
        isImage: function (url) {
            return url.match(/\.(?:gif|jpe?g|png|svg|ico)$/i);
        },
        clearMessage: function () {
            this.$set('message.message', '');
        },
        setMessage: function (message, msg_class) {
            this.$set('message.message', message);
            this.$set('message.msg_class', 'uk-alert-' + (msg_class || 'danger'));
        },
    },

};

</script>
