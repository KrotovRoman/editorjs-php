<?php

use CodexEditor\CodexEditor;
use CodexEditor\CodexEditorException;
use CodexEditor\ConfigLoader;

/**
 * Class GeneralTest
 *
 * Check basic config and block data parsing functionality
 */
class GeneralTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';

    const EMPTY_DATA = '';

    const CONFIGURATION_FILE = TESTS_DIR . "/samples/test-config.json";

    private $config = '';

    public function setUp()
    {
        $this->config = file_get_contents(GeneralTest::CONFIGURATION_FILE);
    }

    public function testValidData()
    {
        new CodexEditor(GeneralTest::SAMPLE_VALID_DATA, $this->config);
    }

    public function testNullInput()
    {
        $callable = function () {
            new CodexEditor('', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'JSON is empty');
    }

    public function testEmptyArray()
    {
        $callable = function () {
            new CodexEditor('{}', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Input array is empty');
    }

    public function testWrongJson()
    {
        $callable = function () {
            new CodexEditor('{[{', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Wrong JSON format: Syntax error');
    }

    public function testValidConfig()
    {
        new ConfigLoader(file_get_contents(TESTS_DIR . "/samples/test-config.json"));
    }

    public function testItemsMissed()
    {
        $callable = function () {
            new CodexEditor('{"s":""}', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Field `blocks` is missing');
    }

    public function testUnicode()
    {
        $callable = function () {
            new CodexEditor('{"s":"😀"}', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Field `blocks` is missing');
    }

    public function testInvalidBlock()
    {
        $callable = function () {
            new CodexEditor('{"blocks":""}', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Blocks is not an array');
    }

    public function testBlocksContent()
    {
        $callable = function () {
            new CodexEditor('{"blocks":["",""]}', $this->config);
        };

        $this->assertException($callable, CodexEditorException::class, null, 'Block must be an Array');
    }
}
