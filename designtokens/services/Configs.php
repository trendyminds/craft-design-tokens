<?php

namespace modules\designtokens\services;

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
		$folder = Craft::getAlias("@modules/designtokens") . DIRECTORY_SEPARATOR . "config";

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
		$folder = Craft::getAlias("@modules/designtokens") . DIRECTORY_SEPARATOR . "config";
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
		$folder = Craft::getAlias("@modules/designtokens") . DIRECTORY_SEPARATOR . "config";
		$config = file_get_contents($folder . DIRECTORY_SEPARATOR . $filename);

		if (!$config) {
			return [];
		}

		return collect(json_decode($config, true))
			->mapWithKeys(fn($data, $key) => [ strtolower($key) => $data ])
			->toArray();
	}
}
