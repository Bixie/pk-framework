<?php
use Pagekit\Application as App;

return [

	'name' => 'bixie/pk-framework',

	'type' => 'extension',

	'main' => 'Bixie\\PkFramework\\FrameworkModule',

	'fieldtypes' => 'fieldtypes',

	'autoload' => [
		'Bixie\\PkFramework\\' => 'src'
	],

    'routes' => [

        '/api/bixpkframework' => [
            'name' => '@bixpkframework/api',
            'controller' => [
                'Bixie\\PkFramework\\Controller\\FrameworkApiController',
                'Bixie\\PkFramework\\Controller\\ImageApiController',
            ]
        ]

    ],

    'resources' => [

		'bixie/pk-framework:' => ''

	],

	'permissions' => [

		'bixpkframework: upload files' => [
			'title' => 'Upload files'
		]

	],

	'settings' => 'settings-bixpkframework',

	'config' => [
        'image_cache_path' => trim(str_replace(App::path(), '', App::get('path.storage') . '/bixpkframework'), '/'),
        'google_maps_key' => '',
	],

	'events' => [
		'view.scripts' => function ($event, $scripts) use ($app) {
            $version = $app->module('bixie/pk-framework')->getVersionKey();
            $scripts->register('vuex', 'bixie/pk-framework:app/bundle/vuex.js', 'vue');
			$scripts->register('framework-settings', 'bixie/pk-framework:app/bundle/settings.js',
                '~extensions', ['version' => $version]);
            $scripts->register('typeahead-bundle', 'bixie/pk-framework:app/assets/typeahead/dist/typeahead.bundle.min.js', ['jquery']);
            $scripts->register('bixie-typeahead', 'bixie/pk-framework:app/bundle/bixie-typeahead.js',
                ['vue', 'typeahead-bundle'], ['version' => $version]);
			$scripts->register('bixie-chartjs', 'bixie/pk-framework:app/bundle/bixie-chartjs.js',
                ['vue'], ['version' => $version]);
			$scripts->register('bixie-pkframework', 'bixie/pk-framework:app/bundle/bixie-framework.js',
                ['vue', 'uikit-lightbox'], ['version' => $version]);
			//register fields
            $dependancies = ['bixie-pkframework', 'uikit-tooltip'];
            foreach ($app->module('bixie/pk-framework')->getFieldTypes() as $fieldType) {
                //pick up dependancies from types
                if ($depends = $fieldType->registerScripts($scripts, $version)) {
                    $dependancies = array_merge($dependancies, $depends);
                }
            }
			$scripts->register('bixie-fieldtypes', 'bixie/pk-framework:app/bundle/bixie-fieldtypes.js',
                $dependancies, ['version' => $version]);
		},

        'view.data' => function ($event, $data) use ($app) {
            //todo can this be done only when framework js is loaded?
            $data->add('$pkframework', [
                'google_maps_key' => $app->module('bixie/pk-framework')->config('google_maps_key')
            ]);
        },

		'console.init' => function ($event, $console) {

			$console->add(new Bixie\PkFramework\Console\Commands\TranslateCommand());

		},
	]

];
