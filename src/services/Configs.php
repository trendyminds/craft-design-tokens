<?php

namespace trendyminds\designtokens\services;

use Craft;
use craft\base\Component;
use craft\helpers\FileHelper;

class Configs extends Component
{
	/**
	 * Get all of the available configs with their full server path
	 *
	 * @return array
	 */
	public function getAll(): array
	{
		$folder = Craft::getAlias("@config") . DIRECTORY_SEPARATOR . "designtokens";

		if (!is_dir($folder)) {
			Craft::info(
				"The $folder does not exist. Create this directory and add your JSON config files for customization.",
				"designtokens"
			);

			return [];
		}

		return FileHelper::findFiles($folder, [
			'only' => ['*.json'],
		]);
	}

	/**
	 * Display the keys of each config option given a config filename
	 *
	 * @param string $filename
	 *
	 * @return array
	 */
	public function getOptionsByFilename(string $filename): array
	{
		$folder = Craft::getAlias("@config") . DIRECTORY_SEPARATOR . "designtokens";
		$config = file_get_contents($folder . DIRECTORY_SEPARATOR . $filename);

		if (!$config) {
			return [];
		}

		return collect(json_decode($config, true))
			->keys()
			->mapWithKeys(fn($name) => [strtolower($name) => ucfirst($name)])
			->toArray();
	}

	/**
	 * Returns the full config by a config's filename
	 *
	 * @param string $filename
	 *
	 * @return array
	 */
	public function getValuesByFilename(string $filename): array
	{
		$folder = Craft::getAlias("@config") . DIRECTORY_SEPARATOR . "designtokens";
		$config = file_get_contents($folder . DIRECTORY_SEPARATOR . $filename);

		if (!$config) {
			return [];
		}

		return collect(json_decode($config, true))
			->mapWithKeys(fn($data, $key) => [ strtolower($key) => $data ])
			->toArray();
	}
}
