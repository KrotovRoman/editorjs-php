<?php

use EditorJS\ConfigLoader;
use EditorJS\EditorJS;
use EditorJS\EditorJSException;

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
        new EditorJS(GeneralTest::SAMPLE_VALID_DATA, $this->config);
    }

    public function testNullInput()
    {
        $callable = function () {
            new EditorJS('', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'JSON is empty');
    }

    public function testEmptyArray()
    {
        $callable = function () {
            new EditorJS('{}', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Input array is empty');
    }

    public function testWrongJson()
    {
        $callable = function () {
            new EditorJS('{[{', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Wrong JSON format: Syntax error');
    }

    public function testValidConfig()
    {
        new ConfigLoader(file_get_contents(TESTS_DIR . "/samples/test-config.json"));
    }

    public function testItemsMissed()
    {
        $callable = function () {
            new EditorJS('{"s":""}', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Field `blocks` is missing');
    }

    public function testUnicode()
    {
        $callable = function () {
            new EditorJS('{"s":"😀"}', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Field `blocks` is missing');
    }

    public function testInvalidBlock()
    {
        $callable = function () {
            new EditorJS('{"blocks":""}', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Blocks is not an array');
    }

    public function testBlocksContent()
    {
        $callable = function () {
            new EditorJS('{"blocks":["",""]}', $this->config);
        };

        $this->assertException($callable, EditorJSException::class, null, 'Block must be an Array');
    }
}
