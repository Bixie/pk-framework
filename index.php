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
	],

	'events' => [
		'view.scripts' => function ($event, $scripts) use ($app) {
			$scripts->register('framework-settings', 'bixie/pk-framework:app/bundle/settings.js', '~extensions');
			$scripts->register('bixie-pkframework', 'bixie/pk-framework:app/bundle/bixie-framework.js', ['vue']);
			//register fields
			$scripts->register('bixie-fieldtypes', 'bixie/pk-framework:app/bundle/bixie-fieldtypes.js', ['bixie-pkframework', 'uikit-tooltip']);
			foreach ($app->module('bixie/pk-framework')->getFieldTypes() as $fieldType) {
				$fieldType->registerScripts($scripts);
			}
		},

		'console.init' => function ($event, $console) {

			$console->add(new Bixie\PkFramework\Console\Commands\TranslateCommand());

		}
	]

];
