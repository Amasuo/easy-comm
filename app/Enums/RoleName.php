<?php

namespace App\Enums;

class RoleName
{
    public const ADMIN = "Admin";
    public const STORE_ADMIN = "Store Admin";
    public const STORE_SIMPLE = "Store Simple";

    public static function toArray(): array
    {
        $reflectionClass = new \ReflectionClass(self::class);
        return array_values($reflectionClass->getConstants());
    }

    public static function toObjectsArray(): array
    {
        $ret = [];
        $reflectionClass = new \ReflectionClass(self::class);
        $roles = array_values($reflectionClass->getConstants());
        foreach ($roles as $role) {
            $object = new \stdClass();
            $object->name = $role;
            $ret[] = $object;
        }
        return $ret;
    }
}