var glob = require("glob");
var path = require("path");
var fieldtypes = {};

glob.sync(path.join(__dirname, 'fieldtypes/*/*.vue')).forEach(function (file) {
    var type = path.basename(file, '.vue');
    fieldtypes['fieldtype-' + type] = './fieldtypes/' + type + '/' + type + '.vue';
});

module.exports = [

    {
        entry: {
            "bixie-fieldtypes": "./app/fieldtypes.vue"
        },
        output: {
            filename: "./app/bundle/[name].js",
            library: "BixieFieldtypes"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue" }
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
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue" },
                {test: /\.html$/, loader: "vue-html"}
            ]
        }
    },

    {
        entry: fieldtypes,
        output: {
            filename: "./app/bundle/[name].js"
        },
        module: {
            loaders: [
                { test: /\.vue$/, loader: "vue" }
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
                {test: /\.vue$/, loader: "vue"}
            ]
        }
    }

];
