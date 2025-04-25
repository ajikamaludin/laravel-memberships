<?php

namespace App\Constants;

class SettingConstant
{
    public static function all()
    {
        return [
            ['key' => 'app_name', 'value' => 'Saga Training', 'type' => 'text'],
            ['key' => 'app_logo', 'value' => '', 'type' => 'image'],
            ['key' => 'name', 'value' => 'Saga Training', 'type' => 'text'],
            ['key' => 'city', 'value' => 'Jawa Barat', 'type' => 'text'],
            ['key' => 'join_fee', 'value' => '10.000', 'type' => 'text'],
        ];
    }
}
