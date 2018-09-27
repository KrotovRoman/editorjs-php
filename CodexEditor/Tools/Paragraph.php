<?php

namespace CodexEditor\Tools;

use CodexEditor\Factory;
use \CodexEditor\Tools\Base;
use \CodexEditor\Interfaces\HTMLPurifyable;
use \HTMLPurifier;

class Paragraph extends Base implements HTMLPurifyable {

    protected $template = 'text';

    public function initialize()
    {
        $this->sanitize();
        return $this->validate();
    }

    /**
     * Clear dirty data
     *
     * @return void
     */
    public function sanitize()
    {
        $allowedTags = 'a[href],br,p,strong,b,i,em';

        if ($this->data['type'] === 'text_limited') {
            $allowedTags = 'a[href],br,p';
        }

        $sanitizer = clone $this->sanitizer;
        $sanitizer->set('HTML.Allowed', $allowedTags);

        $purifier = new HTMLPurifier($sanitizer);

        $this->data['data']['text'] = $purifier->purify($this->data['data']['text']);

    }

    /**
     * Validate input data
     *
     * @return boolean
     */
    public function validate()
    {
        $validType      = is_array($this->data) && in_array($this->data['type'], Factory::getAllowedBlockTypes()['Paragraph']);
        $textNotEmpty   = !empty($this->data['data']['text']);

        if ($validType && $textNotEmpty) {

            return true;
        }

        return null;
    }

    /**
     * Must return HTML template
     * @return string
     */
    public function getTemplate() {
        return $this->template;
    }

}