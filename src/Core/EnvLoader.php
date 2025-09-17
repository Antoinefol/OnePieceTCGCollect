<?php
namespace OnePieceTCGCollect\src\Core;

class EnvLoader
{
    public static function load(string $path = __DIR__ . '/../../../.env'): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignorer les commentaires
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            // Séparer clé=valeur
            [$name, $value] = array_map('trim', explode('=', $line, 2));

            if (!getenv($name)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
