<?php

namespace App\Helpers;

class GeneralHelper
{
    public static function valueTakenForClassAttribute($class, $attribute, $value, $modelId)
    {
        $modelId = intval($modelId);
        $existingRecord = $class::where($attribute, $value)->first();
        return $existingRecord && $existingRecord->id !== $modelId;
    }
}
