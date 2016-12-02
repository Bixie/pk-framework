var assets = __dirname + "/../../../app/assets";

module.exports = [

    {
        entry: {
            "bixie-fieldtypes": "./app/fieldtypes.vue"
        },
        output: {
            filename: "./app/bundle/[name].js",
            library: "BixieFieldtypes"
        },
        resolve: {
            alias: {
                "md5$": assets + "/js-md5/js/md5.js"
            }
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: "vue" },
                {test: /\.html$/, loader: "vue-html"},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015']}}
            ]
        }
    },

    {
        entry: {
            "bixie-framework": "./app/framework.js"
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        resolve: {
            alias: {
                "md5$": assets + "/js-md5/js/md5.js"
            }
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: "vue" },
                {test: /\.html$/, loader: "vue-html"},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015']}}
            ]
        }
    },

    {
        entry: {
            /*pagekit addons*/
            "settings": "./app/components/settings.vue"
        },
        output: {
            filename: "./app/bundle/[name].js"
        },
        externals: {
            "lodash": "_",
            "jquery": "jQuery",
            "uikit": "UIkit",
            "vue": "Vue"
        },
        module: {
            loaders: [
                {test: /\.vue$/, loader: "vue"},
                {test: /\.js/, loader: 'babel', query: {presets: ['es2015']}}
            ]
        }
    }

];
