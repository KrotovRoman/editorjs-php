<?php

use CodexEditor\CodexEditor;

class GeneralTest extends TestCase
{
    const SAMPLE_VALID_DATA = '{"time":1537444483710,"blocks":[{"type":"header","data":{"text":"CodeX Editor","level":2}},{"type":"paragraph","data":{"text":"Привет. Перед вами наш обновленный редактор. На этой странице вы можете проверить его в действии — попробуйте отредактировать или дополнить материал. Код страницы содержит пример подключения и простейшей настройки."}}],"version":"2.0.3"}';

    const EMPTY_DATA = '';

    const CONFIG = TESTS_DIR . "/samples/test-config.json";

    public function testValidData()
    {
        new CodexEditor( GeneralTest::SAMPLE_VALID_DATA, GeneralTest::CONFIG );
    }

    public function testNullInput()
    {
        $callable = function() {
            new CodexEditor('', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'JSON is empty');
    }

    public function testEmptyArray()
    {
        $callable = function() {
            new CodexEditor('{}', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Input array is empty');
    }

    public function testWrongJson()
    {
        $callable = function() {
            new CodexEditor('{[{', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Wrong JSON format: Syntax error');
    }

    public function testItemsMissed()
    {
        $callable = function() {
            new CodexEditor('{"s":""}', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Field `blocks` is missing');
    }

    public function testUnicode()
    {
        $callable = function() {
            new CodexEditor('{"s":"😀"}', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Field `blocks` is missing');
    }

    public function testInvalidBlock()
    {
        $callable = function() {
            new CodexEditor('{"blocks":""}', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Blocks is not an array');
    }

    public function testBlocksContent()
    {
        $callable = function() {
            new CodexEditor('{"blocks":["",""]}', GeneralTest::CONFIG);
        };

        $this->assertException($callable, Exception::class, null, 'Block must be an Array');
    }

}