<?php

namespace CodexEditor\Tools;

use \HTMLPurifier;
use \CodexEditor\Interfaces\Tools;
use \CodexEditor\Interfaces\HTMLPurifyable;

/**
 * Abstract class Base
 * may be used as interface of Tools of Codex.Editor
 * Each Tool must implement this class and define abstract methods
 *
 * @author Khaydarov Murod
 * @author Khaydarov Murod <murod.haydarov@gmail.com>
 * @copyright 2017 Codex Team
 * @license MIT
 *
 * @package CodexEditor\Blocks
 * @var string $data - input json as string
 * @var object $sanitizer - html purifier
 * @var string $template - path to html template of tool
 *
 */

abstract class Base implements Tools {

    /**
     * @var array $data - Block data
     */
    protected $data;

    /**
     * @var object $sanitizer - Purifier
     */
    protected $sanitizer;

    /**
     * @var object $template - HTML content
     */
    protected $template;

    /**
     * Base constructor.
     * @param $data
     */
    public function __construct($data)
    {

        $this->data = $data;

        if ($this instanceof HTMLPurifyable) {

            $this->sanitizer = \HTMLPurifier_Config::createDefault();

            $this->sanitizer->set('HTML.TargetBlank', true);
            $this->sanitizer->set('URI.AllowedSchemes', ['http' => true, 'https' => true]);
            $this->sanitizer->set('AutoFormat.RemoveEmpty', true);

            if (!is_dir('/tmp/purifier')) {
                mkdir('/tmp/purifier', 0777, true);
            }

            $this->sanitizer->set('Cache.SerializerPath', '/tmp/purifier');

        }

    }

    /** Initialize Block */
    abstract function initialize();

    /** Should be extended by Block Class */
    abstract function validate();

    /** Should be extended by Block Class */
    abstract function sanitize();

    /**
     * Returns block 'data'
     * @param Boolean $escapeHTML  pass TRUE to escape HTML entities
     * @return array    with block data
     */
    public function getData($escapeHTML = false)
    {
        return $this->data;
    }

}