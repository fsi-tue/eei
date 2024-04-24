<?php

class I18n implements ArrayAccess
{

    /**
     * @var string
     */
    private mixed $i18nObject;
    /**
     * @var mixed
     */
    private string $language;

    public function __construct()
    {
        // check if query string is set
        $url = $_SERVER['QUERY_STRING'] ?? '';
        parse_str($url, $query);
        // set default language to german
        $lang = $query['lang'] ?? 'de';
        // sanitize language
        $lang = preg_replace('/[^a-z]/', '', $lang);

        $this->language = $lang;
        $localizationFile = __DIR__ . "/$lang.json";
        if (file_exists($localizationFile)) {
            $this->i18nObject = json_decode(file_get_contents($localizationFile), true);
        } else {
            // If the localization file does not exist, use the default language (german)
            $this->i18nObject = json_decode(file_get_contents(__DIR__ . '/de.json'), true);
        }
    }

    /**
     * Translates a string
     *
     * @param $key                   string key of the string to translate
     * @param $insertVariables       array replace variables in the string with the values in this array
     *                               <code>Example: $i18n->translate('hello', array('name' => 'John')) will return
     *                               'Hello John'</code>
     *
     * @return string
     */
    public function translate(string $key, array $insertVariables = array()): string
    {
		// Make the key lowercase
		$key = strtolower($key);

		// If the key does not exist, return an empty string
		if (!array_key_exists($key, $this->i18nObject)) {
			return '';
		}

        $translatedString = $this->i18nObject[$key];

        foreach ($insertVariables as $key => $value) {
            $translatedString = str_replace("{" . $key . "}", $value, $translatedString);
        }
        return $translatedString ?? '';
    }

    public function getLanguage()
    {
        return $this->language;
    }

    #[ReturnTypeWillChange] public function offsetExists($offset): bool
    {
        return isset($this->i18nObject[$offset]);
    }

    #[ReturnTypeWillChange] public function offsetGet($offset)
    {
        return $this->i18nObject[$offset] ?? NULL;
    }

    #[ReturnTypeWillChange] public function offsetSet($offset, $value)
    {
		throw new Exception('Cannot set value in i18n object');
    }

    #[ReturnTypeWillChange] public function offsetUnset($offset)
    {
		throw new Exception('Cannot unset value in i18n object');
    }
}

$i18n = new I18n();
