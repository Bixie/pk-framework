<?php

namespace Bixie\PkFramework\Controller;

use Pagekit\Application as App;

/**
 */
class FrameworkApiController
{
    /**
     * @Route("/{field_type}/{method}", methods="POST", requirements={"field_type"="\w+", "method"="\w+"})
     * @Request({"data": "array"}, csrf=true)
     */
    public function ajaxAction($data = [], $field_type, $method) {
        /** @var \Bixie\PkFramework\FrameworkModule $framework */
        $framework = App::module('bixie/pk-framework');

        if (!$fieldType = $framework->getFieldType($field_type)) {
            return App::abort(400, sprintf('Fieldtype %s not found', $field_type));
        }

        try {

            return call_user_func_array([$fieldType, "{$method}Action"], [$data]);

        } catch (\BadMethodCallException $e) {
            return App::abort(503, $e->getMessage());
        }
    }

}
