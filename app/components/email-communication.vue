<template>

    <div class="uk-form">

        <div class="uk-panel uk-panel-box uk-panel-box-primary">

            <h3>{{ 'Create email' | trans }}</h3>

            <div v-if="sender" class="uk-form-row">
                <label for="form-sender" class="uk-form-label">{{ 'Sender' | trans }}</label>
                <div class="uk-form-controls">
                    <select id="form-sender" v-model="sender" class="uk-width-1-1">
                        <option v-for="sender in senders" :value="$key">
                            {{ sender.name }} - {{ sender.email }}
                        </option>
                    </select>
                </div>
            </div>
            <div v-if="receiver" class="uk-form-row">
                <label for="form-receiver" class="uk-form-label">{{ 'Receiver' | trans }}</label>
                <div class="uk-form-controls">
                    <select id="form-receiver" v-model="receiver" class="uk-width-1-1">
                        <option v-for="receiver in receivers" :value="$key">
                            {{ receiver.name }} - {{ receiver.email }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="uk-form-row">
                <label for="form-template" class="uk-form-label">{{ 'Template' | trans }}</label>
                <div class="uk-form-controls">

                    <div class="uk-grid uk-grid-small" data-uk-grid-margin>
                        <div :class="{'uk-width-8-10': !stacked,'uk-width-1-1': stacked}">

                            <input-typeahead v-if="useTypeahead" v-ref:typeahead class="uk-width-1-1"
                                             :search_text.sync="template_search"
                                             :datasets="datasets"
                                             :on-select="onSelectTypeahead"></input-typeahead>

                            <small v-if="useTypeahead">
                                <a @click="showAllTemplates = !showAllTemplates">
                                    {{showAllTemplates ? 'Hide all templates' : 'Show all templates' | trans }}
                                </a>
                            </small>

                            <select id="form-template" v-model="template" v-show="showSelect" class="uk-width-1-1">
                                <option value="">{{ 'Select template' | trans }}</option>
                                <optgroup v-for="templates in groupedTemplates" :label="$key">
                                    <option v-for="template in templates" :value="template.id">
                                        {{ template.subject }}
                                    </option>
                                </optgroup>
                            </select>
                        </div>
                        <div :class="{'uk-width-2-10': !stacked,'uk-width-1-1': stacked}">
                            <button @click="mailTemplate(template)" type="button"
                                    class="uk-button uk-text-nowrap" :disabled="!template">
                                <i v-spinner="searching" icon="magic"></i>
                                {{ 'Create mail' | trans }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="showLog" class="uk-margin">
            <h3>{{ 'Communication log' | trans }}</h3>

            <email-logs v-ref:maillog :ext_key="ext_key"></email-logs>
        </div>

        <v-modal v-ref:mailmodal :options="{bgclose: false}">
            <div class="uk-modal-header">
                <h3>{{ 'Send email' | trans }}</h3>
            </div>
            <email-modal :type="template"
                         :ext_key="ext_key"
                         :to="mail_data.to"
                         :cc="mail_data.cc"
                         :bcc="mail_data.bcc"
                         :subject="mail_data.subject"
                         :content="mail_data.content"
                         :attachments="attachments"
                         :send-action="sendMail"></email-modal>
        </v-modal>

    </div>


</template>

<script>

    module.exports = {

        props: {
            'templates': Array,
            'ext_key': String,
            'resource': {type: String, default: 'api/emailsender'},
            'senders': {type: Object, default: () => {return {};}},
            'receivers': {type: Object, default: () => {return {};}},
            'attachments': {type: Array, default: () => {return [];}},
            'emailData': {type: Object, default: () => {return {};}},
            'user_id': {type:  Number, default: null},
            'id': {type: [String, Number], default: null},
            'showLog': {type: Boolean, default: true},
            'stacked': {type: Boolean, default: false},
        },

        data() {
            return {
                template_search: '',
                template: '',
                sender: '',
                receiver: '',
                showAllTemplates: false,
                searching: false,
                datasets: {},
                mail_data: {}
            }
        },

        created() {
            if (this.useTypeahead) {
                this.datasets.emailtemplates = new BloodhoundDataset(this, 'emailtemplates', {
                    keys: {
                        label: 'type_label',
                        subtitle: 'subject',
                        extra_search: 'subject',
                    },
                    display(obj) {
                        return `${obj.type_label} - ${obj.subject}`;
                    },
                });
            }

            this.Mail = this.$resource(this.resource, {}, {
                'template': {method: 'post', url: `${this.resource}/template{/id}`},
                'sendmail': {method: 'post', url: `${this.resource}/sendmail{/id}`}
            });
            if (_.size(this.senders)) {
                this.sender = Object.keys(this.senders)[0];
            }
            if (_.size(this.receivers)) {
                this.receiver = Object.keys(this.receivers)[0];
            }
        },

        compiled() {
            if (this.useTypeahead) {
                this.$refs.typeahead.initDatasets({emailtemplates: this.templates});
            }
        },

        events: {
            'after.email.send': function (type, mail) {
                this.$refs.mailmodal.close();
                if (this.showLog) {
                    this.$refs.maillog.load();
                }
                return true; //bubble event
            },
            'email.cancel': function (type) {
                this.$refs.mailmodal.close();
            }
        },

        computed: {
            useTypeahead() {
                return window.Bloodhound !== undefined;
            },
            showSelect() {
                return !this.useTypeahead || this.showAllTemplates;
            },
            groupedTemplates() {
                return _.groupBy(this.templates, 'type_label');
            },
        },

        methods: {
            onSelectTypeahead(template) {
                this.template = template.id;
            },
            mailTemplate(template_id) {
                this.searching = true;
                this.Mail.template({id: this.id}, {
                    template_id,
                    data: _.merge({
                        sender: this.senders[this.sender] || {name: '', email: ''},
                        receiver: this.receivers[this.receiver] || {name: '', email: ''},
                    }, this.emailData),
                    user_id: this.user_id,
                }).then(res => {
                    this.mail_data = _.merge({
                        to: '',
                        cc: '',
                        bcc: '',
                        subject: '',
                        content: ''
                    }, res.data.mail);
                    this.searching = false;
                    this.template_search = '';
                    this.$refs.mailmodal.open();
                }, res => {
                    this.$notify(res.data, 'danger');
                    this.searching = false;
                });

            },

            sendMail: function (template_id, mail) {
                return this.Mail.sendmail({id: this.id}, {
                    template_id, mail,
                    data: _.merge({
                        sender: this.senders[this.sender],
                        receiver: this.receivers[this.receiver],
                    }, this.emailData),
                    user_id: this.user_id})
                        .then(res => this.$notify(res.data.message, 'success'), res => this.$notify(res.data, 'danger'));
            }
        },

        components: {
            'email-modal': require('./email-modal.vue'),
            'email-logs': require('./email-logs.vue'),

        }
    };

</script>
