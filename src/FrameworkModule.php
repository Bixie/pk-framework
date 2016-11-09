<?php

namespace Bixie\PkFramework;

use Bixie\PkFramework\Helpers\ImageHelper;
use Pagekit\Application as App;
use Pagekit\Module\Module;
use Bixie\PkFramework\FieldType\FieldTypeBase;

class FrameworkModule extends Module {
	/**
	 * @var FieldTypeBase[]
	 */
	protected $fieldTypes;

	protected $fieldExtensions = ['bixie/formmaker', 'bixie/userprofile', 'bixie/customcontent'];

	/**
	 * {@inheritdoc}
	 */
	public function main (App $app) {
		$app['filter']->register('bixfilesize', 'Bixie\PkFramework\Filter\FileSizeFilter');
        $app->on('boot', function ($event, $app) {
            $app->extend('view', function ($view) use ($app) {
                return $view->addHelper(new ImageHelper($app));
            });
        });
	}

	/**
	 * @param  string $type
	 * @return FieldTypeBase
	 */
	public function getFieldType ($type) {
		$types = $this->getFieldTypes();

		return isset($types[$type]) ? $types[$type] : null;
	}

	/**
	 * @return array
	 */
	public function getFieldTypes ($extension = null) {
		//todo cache this
		if (!$this->fieldTypes) {

			$this->fieldTypes = [];
			/** @noinspection PhpUnusedLocalVariableInspection */
			$app = App::getInstance(); //available for index.php files
			$paths = [];

			foreach (App::module() as $module) {
				if ($module->get('fieldtypes')) {
					$paths = array_merge($paths, glob(sprintf('%s/%s/*/index.php', $module->path, $module->get('fieldtypes')), GLOB_NOSORT) ?: []);
				}
			}

			foreach ($paths as $p) {
				$package = array_merge ([
					'id' => '',
					'path' => dirname($p),
					'main' => '',
					'extensions' => $this->fieldExtensions,
					'class' => '\Bixie\PkFramework\FieldType\FieldType',
					'resource' => 'bixie/pk-framework:app/bundle',
					'config' => [
						'hasOptions' => 0,
						'readonlyOptions' => 0,
						'required' => 0,
						'multiple' => 0,
					],
					'dependancies' => [],
					'styles' => [],
					'getOptions' => '',
					'prepareValue' => '',
					'formatValue' => ''
				], include($p));
				$this->registerFieldType($package);
			}

		}
		if ($extension) {
			return array_filter($this->fieldTypes, function ($fieldType) use ($extension) {
				/** @var FieldTypeBase $fieldType */
			    return in_array($extension, $fieldType->getExtensions());
			});
		}

		return $this->fieldTypes;
	}

	/**
	 * Register a field type.
	 * @param array $package
	 */
	protected function registerFieldType ($package) {
		$loader = App::get('autoloader');
		if (isset($package['autoload'])) {
			foreach ($package['autoload'] as $namespace => $path) {
				$loader->addPsr4($namespace, $this->resolvePath($package, $path));
			}
		}
		$this->fieldTypes[$package['id']] = new $package['class']($package);
	}

    /**
     * @return bool|string
     */
    public function getImageCachepath () {
        if ($folder = App::locator()->get(App::module('bixie/pk-framework')->config('image_cache_path'))
                and is_writable($folder)) { //all fine, quick return
            return $folder;
        }
        //try to create user-folder
        App::file()->makeDir($folder, 0755);
        if (!file_exists($folder)) {
            //create default folder
            $folder = $this->app['path.storage'] . '/bixpkframework';
            if (!file_exists($folder)) {
                App::file()->makeDir($folder, 0755);
            }
        }
        if (!file_exists($folder) || !is_writable($folder)) { //give up
            return false;
        }
        return $folder;
    }

	/**
	 * Resolves a path to a absolute module path.
	 *
	 * @param  array  $package
	 * @param  string $path
	 * @return string
	 */
	protected function resolvePath($package, $path) {

		$path = strtr($path, '\\', '/');

		if (!($path[0] == '/' || (strlen($path) > 3 && ctype_alpha($path[0]) && $path[1] == ':' && $path[2] == '/'))) {
			$path = $package['path']."/$path";
		}

		return $path;
	}

}
