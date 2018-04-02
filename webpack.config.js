
module.exports = [

    {
        entry: {
            'bixie-fieldtypes': './app/fieldtypes.js',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: 'vue',},
                {test: /\.html$/, loader: 'vue-html',},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015',],},},
            ],
        },
    },

    {
        entry: {
            'bixie-typeahead': './app/typeahead.js',
            'bixie-chartjs': './app/chartjs.js',
            'bixie-framework': './app/framework.js',
            'vuex': './app/vuex.js',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: 'vue',},
                {test: /\.html$/, loader: 'vue-html',},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015',],},},
            ],
        },
    },

    {
        entry: {
            /*pagekit addons*/
            'settings': './app/components/settings.vue',
        },
        output: {
            filename: './app/bundle/[name].js',
        },
        externals: {
            'lodash': '_',
            'jquery': 'jQuery',
            'uikit': 'UIkit',
            'vue': 'Vue',
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: 'vue',},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015',],},},
            ],
        },
    },

];
