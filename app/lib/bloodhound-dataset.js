/**
 * Dataset for Typeahead
 * @param $vm
 * @param name
 * @return {*}
 * @constructor
 */
/*globals _, Promise*/

//add nearby search suggestions
const parentSearch = window.SearchIndex.prototype.search;
window.SearchIndex.prototype.search = function (query) {
    const suggestions = parentSearch.call(this, query);
    const ids = suggestions.map(obj => obj.id);
    parentSearch.call(this, 'dist_match').forEach(obj => {
        if (ids.indexOf(obj.id) === -1) {
            suggestions.push(obj);
        }
    });
    return suggestions;
};


function BloodhoundDataset($vm, name, dataset_options) {

    let engine;
    let dataset;
    dataset_options = _.merge({
        results_title: '{0}%count% results found|{1}%count% result found|]1,Inf[%count% results found',
        keys: {
            id: 'id',
            label: 'name',
            subtitle: 'description',
            extra_search: [],
        },
        onSelect: suggestion => suggestion,
        display: false,
    }, dataset_options);

    const charMap = {
        'a': /[àáâä]/gi,
        'c': /[ç]/gi,
        'e': /[èéêë]/gi,
        'i': /[ïìí]/gi,
        'o': /[ôöóò]/gi,
        'oe': /[œ]/gi,
        'u': /[üû]/gi,
        'n': /[ñ]/gi,
    };

    const normalize = function (str) {
        _.forIn(charMap, function (regex, normalized) {
            str = str.replace(regex, normalized);
        });
        return str;
    };

    const datumTokenizer = function (obj) {
        let str = obj[dataset_options.keys.label];
        if (dataset_options.keys.extra_search.length) {
            dataset_options.keys.extra_search.forEach(key => {
                if (obj[key]) {
                    str += ` ${obj[key]}`;
                }
            });
        }
        return window.Bloodhound.tokenizers.whitespace(normalize(str));
    };

    const queryTokenizer = function (query) {
        return window.Bloodhound.tokenizers.whitespace(normalize(query));
    };

    const identify = function (obj) {
        return obj[dataset_options.keys.id];
    };

    const sorter = function (a, b) {
        if (a[dataset_options.keys.label] < b[dataset_options.keys.label]) {
            return -1;
        }
        if (a[dataset_options.keys.label] > b[dataset_options.keys.label]) {
            return 1;
        }
    };

    const templates = {
        notFound(result) {
            return ``;
        },
        pending(result) {
            const message = $vm.$trans('Searching for %query%...', {query: result.query,});
            return `<div>${message}</div>`;
        },
        header(result) {
            const count = result.suggestions.length;
            const title = $vm.$transChoice(dataset_options.results_title, count, {count,});
            return `<small class="tt-dataset-header">${title}</small>`;
        },
        footer(result) {
            return ``;
        },
        suggestion(obj) {
            let subline = obj[dataset_options.keys.subtitle] ? `<br><small>${obj[dataset_options.keys.subtitle]}</small>` : '';
            return `<div>${obj[dataset_options.keys.label]}${subline}</div>`;
        },
    };

    const display = function (obj) {
        return dataset_options.display ? dataset_options.display(obj) : obj[dataset_options.keys.label];
    };

    /**
     *
     * @param local
     * @param remote
     * @param prefetch
     * @return Promise resolved wth engine when initialized
     */
    function init(local, remote, prefetch) {

        const opts = {
            initialize: false,
            local,
            identify,
            sorter,
            queryTokenizer,
            datumTokenizer,
        };
        if (remote) {
            opts.remote = remote;
        }
        if (prefetch) {
            opts.prefetch = prefetch;
        }
        engine = new window.Bloodhound(opts);

        return new Promise((resolve, reject) => {
            engine.initialize().done(() => {
                dataset = {
                    name,
                    source: engine,
                    display,
                    templates,
                };
                resolve(dataset);
            }).fail(() => reject('Error initializing dataset ' + name));
        });
    }

    /**
     *
     * @param suggestion
     */
    function onSelect(suggestion) {
        return new Promise((resolve, reject) => {
            resolve(dataset_options.onSelect(suggestion));
        });
    }

    return {
        name,
        init,
        onSelect,
    };
}

export default BloodhoundDataset;