<?php

namespace App\Services;

class ColorUtil
{
    public static function subtract(string $hex, array $delta): string
    {
        $hsl = self::hexToHsl($hex);

        $newColor = [];
        foreach ($hsl as $key => $value) {
            $newColor[] = $value - $delta[$key];
        }

        return self::hslToHex($newColor);
    }

    public static function rgbDelta(string $hex1, string $hex2): array
    {
        $hsl1 = self::hexToHsl($hex1);
        $hsl2 = self::hexToHsl($hex2);

        $delta = [];
        foreach ($hsl1 as $key => $value) {
            $delta[] = $value - $hsl2[$key];
        }

        return $delta;
    }

    public static function hexToHsl($hex): array
    {
        $hex = [$hex[0] . $hex[1], $hex[2] . $hex[3], $hex[4] . $hex[5]];
        $rgb = array_map(
            static function ($part) {
                return hexdec($part) / 255;
            },
            $hex
        );

        $max = max($rgb);
        $min = min($rgb);

        $l = ($max + $min) / 2;

        $h = 0;
        if ($max === $min) {
            $h = $s = 0;
        } else {
            $diff = $max - $min;
            $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

            switch ($max) {
                case $rgb[0]:
                    $h = ($rgb[1] - $rgb[2]) / $diff + ($rgb[1] < $rgb[2] ? 6 : 0);
                    break;
                case $rgb[1]:
                    $h = ($rgb[2] - $rgb[0]) / $diff + 2;
                    break;
                case $rgb[2]:
                    $h = ($rgb[0] - $rgb[1]) / $diff + 4;
                    break;
            }

            $h /= 6;
        }

        return [$h, $s, $l];
    }

    public static function hslToHex($hsl): string
    {
        [$h, $s, $l] = $hsl;

        if ($s === 0) {
            $r = $g = $b = 1;
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = self::hue2rgb($p, $q, $h + 1 / 3);
            $g = self::hue2rgb($p, $q, $h);
            $b = self::hue2rgb($p, $q, $h - 1 / 3);
        }

        return self::rgb2hex($r) . self::rgb2hex($g) . self::rgb2hex($b);
    }

    public static function hue2rgb($p, $q, $t)
    {
        if ($t < 0) {
            ++$t;
        }
        if ($t > 1) {
            --$t;
        }
        if ($t < 1 / 6) {
            return $p + ($q - $p) * 6 * $t;
        }
        if ($t < 1 / 2) {
            return $q;
        }
        if ($t < 2 / 3) {
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        }

        return $p;
    }

    public static function rgb2hex($rgb): string
    {
        return str_pad(dechex($rgb * 255), 2, '0', STR_PAD_LEFT);
    }
}
