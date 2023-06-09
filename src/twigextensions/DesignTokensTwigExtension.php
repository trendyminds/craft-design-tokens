<?php

namespace trendyminds\designtokens\twigextensions;

use Craft;
use craft\helpers\Json;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DesignTokensTwigExtension extends AbstractExtension
{
	public function getFunctions()
	{
		return [
			new TwigFunction('designTokens', function ($data) {
				$filename = "$data.json";
				$folder = Craft::getAlias("@config") . DIRECTORY_SEPARATOR . "designtokens";

				// Try to locate the JSON file within the design tokens directory
				try {
					$config = file_get_contents($folder . DIRECTORY_SEPARATOR . $filename);
				} catch(\Exception $e) {
					throw new \Exception("Could not locate a JSON config file for `$filename`. Make sure this file exists and is located in $folder");
				}

				// Attempt to decode the JSON data
				$data = Json::decodeIfJson($config);

				// If it's invalid throw an error
				if (! is_array($data)) {
					throw new \Exception("Could not properly decode your JSON config file at `$filename`. Make sure this file uses valid JSON.");
				}

				return $data;
			}),
		];
	}
}
