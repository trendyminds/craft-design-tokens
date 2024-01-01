<?php

namespace trendyminds\designtokens\models;

use craft\base\Model;
use trendyminds\designtokens\DesignTokens;

class TokenModel extends Model
{
    public string $config;

    public string $key;

    /**
     * Outputs the value(s) located in the config based on the selected key
     */
    public function __toString(): string
    {
        $key = strtolower($this->key);
        $allOptions = DesignTokens::$instance->configs->getValuesByFilename($this->config);

        $selected = $allOptions[$key];

        if (gettype($selected) !== 'string') {
            return '';
        }

        return $selected ?? '';
    }

    /**
     * Grabs a single value by the object key (field.get('text'))
     *
     * @param  string|null  $option The option to pluck
     * @return string|null Either return the value from the key/value pair in the config or null
     */
    public function get(?string $option = null): ?string
    {
        $key = strtolower($this->key);

        if ($option === null) {
            return $this->__toString();
        }

        $allOptions = DesignTokens::$instance->configs->getValuesByFilename($this->config);

        /**
         * First check if we have an object of pluckable classes:
         * "red": {
         *   "bg": "bg-red-50",
         *   "text": "text-red-700"
         * }
         *
         * Then check if we have a single value:
         * {
         * 	"large": "py-16"
         * }
         *
         * And fallback to a null value
         */
        return $allOptions[$key][$option] ?? $allOptions[$option] ?? null;
    }
}
