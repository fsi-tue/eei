<?php

class Localizer implements ArrayAccess {

    /**
     * @var string
     */
    private $localizationObject;
    /**
     * @var mixed
     */
    private $lang;

    public function __construct() {
        $url = $_SERVER['QUERY_STRING'];
        parse_str($url, $query);
        // set default language to german
        $lang = isset($query['lang']) ? $query['lang'] : 'de';
        // sanitize language
        $lang = preg_replace('/[^a-z]/', '', $lang);

        $this->lang = $lang;
        $localizationFile = __DIR__ . "/$lang.json";
        if (file_exists($localizationFile)) {
            $this->localizationObject = json_decode(file_get_contents($localizationFile), true);
        } else {
            // If the localization file does not exist, use the default language (german)
            $this->localizationObject = json_decode(file_get_contents(__DIR__ . '/de.json'), true);
        }
    }

    function translate($key) {
        return $this->localizationObject[$key];
    }

    function getLang() {
        return $this->lang;
    }

    public function offsetExists($offset)
    {
        return isset($this->localizationObject[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->localizationObject[$offset]) ? $this->localizationObject[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {}

    public function offsetUnset($offset)
    {}
}