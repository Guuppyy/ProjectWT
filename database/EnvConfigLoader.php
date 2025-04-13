<?php

namespace project\database;

class EnvConfigLoader
{


    public static function load(string $filename): array {
        $config = [];
        $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) { // Игнорируем комментарии
                continue;
            }
            [$key, $value] = explode('=', trim($line), 2);
            $config[$key] = $value;
        }

        return $config;
    }


}