<?php

namespace trendyminds\designtokens\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Json;
use trendyminds\designtokens\models\TokenModel;
use trendyminds\designtokens\DesignTokens;
use yii\db\Schema;

class DesignTokensField extends Field
{
	public $config;

	public static function displayName(): string
	{
		return "Design Tokens";
	}

	public function rules(): array
	{
		return parent::rules();
	}

	public function getContentColumnType(): string
	{
		return Schema::TYPE_STRING;
	}

	public function normalizeValue($value, ElementInterface $element = null): mixed
	{
		if ($value instanceof TokenModel) {
			return $value;
		}

		if (is_array($value)) {
			$value = Json::encode($value);
		}

		if ($value === null) {
			return null;
		}

		$data = json_decode($value, true);
		return new TokenModel($data);
	}

	public function serializeValue($value, ElementInterface $element = null): mixed
	{
		return parent::serializeValue($value, $element);
	}

	public function getSettingsHtml(): ?string
	{
		// Get all of the available configs and return just their filename
		$configs = collect(DesignTokens::$instance->configs->getAll())
			->map(function ($path) {
				$folder = Craft::getAlias("@config") . DIRECTORY_SEPARATOR . "designtokens" . DIRECTORY_SEPARATOR;
				return str_replace( $folder, '', $path );
			})
			->mapWithKeys(fn($filename) => [$filename => $filename])
			->toArray();

		return Craft::$app->getView()->renderTemplate('designtokens/field-settings', [
			'field' => $this,
			'options' => $configs,
		]);
	}

	public function getInputHtml($value, ElementInterface $element = null): string
	{
		$id = Craft::$app->getView()->formatInputId($this->handle);
		$namespacedId = Craft::$app->getView()->namespaceInputId($id);

		return Craft::$app->getView()->renderTemplate('designtokens/field', [
			'id' => $id,
			'field' => $this,
			'value' => $value->key ?? null,
			'name' => $this->handle,
			'namespacedId' => $namespacedId,
			'options' =>
				$this->config
					? DesignTokens::$instance->configs->getOptionsByFilename($this->config)
					: null,
		]);
	}
}
