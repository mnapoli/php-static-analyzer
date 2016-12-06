<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test;

use PhpAnalyzer\File;
use PHPUnit\Framework\TestCase;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FunctionalTest extends TestCase
{
    /**
     * @dataProvider provideTestFiles
     */
    public function test serialize(string $filename)
    {
        $file = new File($filename . '.php');
        $expectedJson = file_get_contents($filename . '.json');

        // Test serialization
        self::assertEquals($expectedJson, json_encode($file->toArray(), JSON_PRETTY_PRINT) . "\n");

        // Test deserialization
        self::assertEquals($file, File::fromArray(json_decode($expectedJson, true)));
    }

    public static function provideTestFiles()
    {
        $files = glob(__DIR__ . '/*/*.php');
        return array_map(function (string $file) {
            $file = substr($file, strlen(__DIR__) + 1);
            return [
                substr($file, 0, strlen($file) - 4),
            ];
        }, $files);
    }

    private function assertJsonSerialization($expectedFile, File $file)
    {
        $json = json_encode($file->toArray(), JSON_PRETTY_PRINT) . "\n";

        self::assertEquals(file_get_contents($expectedFile), $json);
    }
}
