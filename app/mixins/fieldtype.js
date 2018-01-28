/*globals _*/

module.exports = {

    props: {'isAdmin': Boolean, 'field': Object, 'model': Object, 'form': Object,},

    data: () => ({
        inputValue: '',
    }),

    computed: {
        fieldValue() {
            if (this.isAdmin) {
                return this.field.data;
            }
            return this.model[this.field.slug];
        },
        valuedata() {
            if (this.fieldValue.value.length) {
                return this.fieldValue.value.map((value, idx) => _.assign({'value': value,}, this.fieldValue.data['data' + idx]));
            }
            return [];
        },
        allowNewValue() {
            return this.field.data.repeatable && this.fieldValue.value.length < (this.field.data.max_repeat || 10);
        },
        fieldMultiple() {
            return !!this.field.data.multiple;
        },
        fieldRequired() {
            return (this.field.data.required && !this.isAdmin);
        },
        fieldRequiredMessage() {
            return this.field.data.requiredError || this.$trans('Please enter a value');
        },
        fieldLabel() {
            return this.isAdmin ? this.$trans('Default value') : this.field.label;
        },
    },

    watch: {
        'inputValue'(value) {
            this.fieldValue.value = this.fieldMultiple ? value : [value,];
        },
    },

    created() {
        this.inputValue = this.fieldMultiple ? this.fieldValue.value : String(this.fieldValue.value);
        if (!this.fieldValue.data || this.fieldValue.data.length === 0) {
            this.fieldValue.data = {}; //fix datatype
        }
    },

    methods: {
        fieldInvalid(form, idx) {
            idx = idx !== undefined ? idx : '';
            return form && form[this.fieldid + idx] ? form[this.fieldid + idx].invalid : false;
        },
        classes(classes_array, classes_string) {
            return (classes_array || []).concat(String(classes_string || '').split(' '));
        },
        addValue(value, valuedata) {
            this.fieldValue.value.push(value);
            this.$set('fieldValue.data.data' + (this.fieldValue.value.length - 1), valuedata || {'value': value,});
        },
        removeValue(value) {
            const data = {};
            if (value) {
                this.fieldValue.value.$remove(value);
                this.fieldValue.value.forEach((value, idx) => {
                    data['data' + idx] = this.getValuedata(value);
                });
            } else {
                this.fieldValue.value = [];
            }
            this.fieldValue.data = data;
        },
        valuedataModel(idx) {
            return this.fieldValue.data['data' + idx] || {'value': this.fieldValue.value[idx] || '',};
        },
        getValuedata(value) {
            return _.find(this.fieldValue.data, 'value', value) || {'value': value,};
        },

    },

};