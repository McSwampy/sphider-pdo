<?php namespace Templating;

// Set up use cases for Twig
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Manager {

    public static Environment $twig;

    private static $extraData = [];

    public static function AddItem(string $key, $data) {
        self::$extraData[$key] = $data;
    }

    public static function AddItems(array $data) {
        self::$extraData = array_merge(self::$extraData, $data);
    }

    public static function RemoveItem(string $key): void {
        unset(self::$extraData[$key]);
    }

    /**
     * Initialize Twig environment
     * @param string $templatesPath Path to the templates directory
     */
    public static function init(string $templatesPath = __DIR__ . '/template_files', string $cachePath = __DIR__ . '/template_cache') {
        $loader = new FilesystemLoader($templatesPath);
        self::$twig = new Environment($loader, [
            'cache' => $cachePath,
            'auto_reload' => true, // automatically reload templates if they change
        ]);
        // self::$extraData = constant('settings');
    }

    /**
     * Load a Twig template and return rendered content
     * @param string $templateName
     * @param array $data
     * @return string
     */
    public static function loadTemplate(string $templateName, array $data = []): string {
        if (!isset(self::$twig)) {
            throw new \Exception("Twig environment is not initialized. Call Templates::init() first.");
        }

        return self::$twig->render($templateName, array_merge(self::$extraData, $data));
    }

    /**
     * Render a Twig template directly to output
     * @param string $templateName
     * @param array $data
     */
    public static function renderTemplate(string $templateName, array $data = []) {
        echo self::loadTemplate($templateName, array_merge(self::$extraData, $data));
        exit;
    }

    public static function ShowTemplate(string $templateName, array $data = []) {
        echo self::loadTemplate($templateName, array_merge(self::$extraData, $data));
    }

    public static function parameters(): array {
        return self::$extraData;
    }
    
}