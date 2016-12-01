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

        $action = "{$method}Action";

        return call_user_func_array([$fieldType, $action], [$data]);
    }

}
