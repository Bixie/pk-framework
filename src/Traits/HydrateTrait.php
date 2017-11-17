<?php

namespace Bixie\PkFramework\Traits;


trait HydrateTrait {

    /**
     * @param array $data
     * @return self|object
     */
    public static function hydrate (array $data) {
        return self::getManager()->load(self::getMetadata(), $data, true, true);
    }


}