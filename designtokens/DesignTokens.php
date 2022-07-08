<?php

namespace modules\designtokens;

use Craft;
use craft\web\View;
use craft\services\Fields;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\RegisterComponentTypesEvent;
use modules\designtokens\fields\DesignTokensField;
use modules\designtokens\services\Configs;

use yii\base\Module;
use yii\base\Event;

class DesignTokens extends Module
{
	public static $instance;

	public function __construct($id, $parent = null, array $config = [])
	{
		// Define an alias to the module for any paths we use
		Craft::setAlias('@modules/designtokens', $this->getBasePath());

		// Base template directory
		Event::on(
			View::class,
			View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
			function (RegisterTemplateRootsEvent $e
		) {
			if (is_dir($baseDir = $this->getBasePath() . DIRECTORY_SEPARATOR . 'templates')) {
				$e->roots[$this->id] = $baseDir;
			}
		});

		// Set this as the global instance of this module class
		static::setInstance($this);
		parent::__construct($id, $parent, $config);
	}

	public function init()
	{
		parent::init();
		self::$instance = $this;

		$this->setComponents([
			'configs' => Configs::class
		]);

		// Register our fields
		Event::on(
			Fields::class,
			Fields::EVENT_REGISTER_FIELD_TYPES,
			function (RegisterComponentTypesEvent $event) {
				$event->types[] = DesignTokensField::class;
			}
		);
	}
}
