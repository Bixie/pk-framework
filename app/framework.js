//components
import InputCategory from './components/input-category.vue';
import InputDateBix from './components/input-date-bix.vue';
import PeriodSelection from './components/period-selection.vue';
import InputTags from './components/input-tags.vue';
import InputFolder from './components/input-folder.vue';
import InputLocation from './components/input-location.vue';
import InputMultiselect from './components/input-multiselect.vue';
import InputProps from './components/input-props.vue';
import SelectsProps from './components/selects-props.vue';
import InputPrices from './components/input-prices.vue';
import InputRelatedItem from './components/input-related-item.vue';
import InputRelatedItems from './components/input-related-items.vue';
import TableList from './components/table-list.vue';
import EmailCommunication from './components/email-communication.vue';
import InputFile from './components/input-file.vue';
//directives
import Spinner from './directives/spinner';
//partials
import FieldtypeBasic from './templates/fieldtype-basic.html';
import FieldtypeSettings from './templates/fieldtype-settings.html';
import FieldtypeAppearance from './templates/fieldtype-appearance.html';
//filters
import filterProps from './lib/props';
//form fields
import formFields from './form-fields/fields';


if (window.Vue) {
    //components
    window.Vue.component('input-category', InputCategory);
    window.Vue.component('input-date-bix', (resolve, reject) => {
        window.Vue.asset({
            js: [
                'app/assets/uikit/js/components/autocomplete.min.js',
                'app/assets/uikit/js/components/datepicker.min.js',
                'app/assets/uikit/js/components/timepicker.min.js',
            ],
        }).then(() => resolve(InputDateBix));
    });
    window.Vue.component('input-file', function (resolve, reject) {
        window.Vue.asset({
            js: [
                'app/assets/uikit/js/components/upload.min.js',
                'app/system/modules/finder/app/bundle/panel-finder.js',
            ],
        }).then(() => resolve(InputFile));
    });
    window.Vue.component('input-folder', (resolve, reject) => {
        window.Vue.asset({
            js: [
                'app/assets/uikit/js/components/upload.min.js',
                'app/system/modules/finder/app/bundle/panel-finder.js',
            ],
        }).then(() => resolve(InputFolder))
    });
    window.Vue.component('period-selection', PeriodSelection);
    window.Vue.component('input-tags', InputTags);
    window.Vue.component('input-location', InputLocation);
    window.Vue.component('input-multiselect', InputMultiselect);
    window.Vue.component('input-props', InputProps);
    window.Vue.component('selects-props', SelectsProps);
    window.Vue.component('input-prices', InputPrices);
    window.Vue.component('input-related-item', InputRelatedItem);
    window.Vue.component('input-related-items', InputRelatedItems);
    window.Vue.component('table-list', TableList);
    window.Vue.component('email-communication', EmailCommunication);
    //directives
    window.Vue.directive('spinner', Spinner);
    //partials
    window.Vue.partial('fieldtype-basic', FieldtypeBasic);
    window.Vue.partial('fieldtype-settings', FieldtypeSettings);
    window.Vue.partial('fieldtype-appearance', FieldtypeAppearance);
    //filters
    filterProps(window.Vue);
    //fields
    formFields(window.Vue);

}
