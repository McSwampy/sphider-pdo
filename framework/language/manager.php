<?php namespace Language;

class Manager {

    public static string $usingLang = 'en';

    public static string $langDir = __DIR__.'/langs';

    public static array $langStrings = [];
    
    public static function InitLang(string $language = 'en') {

        if (!preg_match('/^[a-z]{2}(?:-[A-Z]{2})?$/', $language)) {
            $language = 'en';
        }

        $languageFile = self::$langDir . '/' . $language . '-language.php';

        if (!file_exists($languageFile)) {
            $language = 'en';
            $languageFile = self::$langDir . '/en-language.php';
        }

        self::$usingLang = $language;
        self::$langStrings = include $languageFile;

    }

}