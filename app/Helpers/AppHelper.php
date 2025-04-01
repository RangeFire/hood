<?php

namespace App\Helpers;


class AppHelper {

    static $dbSyncAES_key_1 = '556A586E3272357538782F413F442A472D4B6150645367566B59703373367639';

    static $imageFileExt = [
        '.jpeg',
        '.png',
        '.gif',
        '.svg+xml',
        '.webp',
    ];

    // Price Config for our products
    static $prices = [
        'oneMonth' => '6.99',
        'oneThreeMonths' => '5.99',
        'monitoringProMonth' => '3.99',
        'monitoringProThreeMonths' => '3.99',
        'brandingMonth' => '6.99',
        'brandingThreeMonths' => '6.99',
        'supportMonth' => '3.99',
        'supportThreeMonths' => '3.99',
    ];

    // GameTypes and GameQ GameCodes
    static $gameTypes = [
        'Minecraft' => 'minecraft',
        'FiveM' => 'gta5m',
        'Arma3' => 'arma3',
        'Rust' => 'rust',
        'Ark' => 'arkse',
        'AltV' => 'altv',
        'Spaceengineers' => 'spaceengineers',
    ];

    public static function isImage($name) {
        foreach(self::$imageFileExt as $ext) {
            if(strpos($name, $ext) !== false) {
                return true;
            }
        }
        return false;
    }

}


?>