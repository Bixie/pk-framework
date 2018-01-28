import InputTypeahead from './components/input-typeahead.vue';
import BloodhoundDataset from './lib/bloodhound-dataset';

if (window.Vue) {

    window.Vue.component('input-typeahead', InputTypeahead);
    window.BloodhoundDataset = BloodhoundDataset;

}
