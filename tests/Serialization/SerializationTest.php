<?php
declare(strict_types = 1);

namespace PhpAnalyzer\Test\Serialization;

use PhpAnalyzer\File;
use PhpAnalyzer\Test\FakeScope;
use PHPUnit\Framework\TestCase;

/**
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SerializationTest extends TestCase
{
    /**
     * @dataProvider provideTestFiles
     */
    public function test serialize(string $filename)
    {
        chdir(__DIR__);

        $file = new File(new FakeScope, $filename . '.php');

        $generatedJson = json_encode($file->toArray(), JSON_PRETTY_PRINT) . "\n";

        // Update JSON file containing expected result if we are in "update" mode
        if (self::isUpdate()) {
            file_put_contents($filename . '.json', $generatedJson);
        }

        $expectedJson = file_get_contents($filename . '.json');

        // Test serialization
        self::assertEquals($expectedJson, $generatedJson);

        // Test deserialization
        self::assertEquals($file, File::fromArray(json_decode($expectedJson, true), new FakeScope));
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

    public static function isUpdate()
    {
        return in_array('--update', $_SERVER['argv'], true);
    }
}
