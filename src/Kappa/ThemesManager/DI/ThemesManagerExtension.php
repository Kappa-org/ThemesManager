<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager\DI;

use Kappa\ThemesManager\InvalidArgumentException;
use Kappa\ThemesManager\Mapping\MaskType;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Helpers;
use Nette\DI\ContainerBuilder;
use Nette\DI\Statement;

/**
 * Class ThemesManagerExtension
 * @package Kappa\ThemesManager\DI
 */
class ThemesManagerExtension extends CompilerExtension
{
	/** @var array */
	private $defaultSectionConfig = [
		'helpers' => [],
		'macros' => [],
		'params' => [
			'themeDir' => null
		],
		'pathMasks' => [
			MaskType::LAYOUTS => [],
			MaskType::PRESENTERS => []
		]
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('formatter'))
			->setClass('Kappa\ThemesManager\Mapping\Formatter');

		$builder->addDefinition($this->prefix('themeFactory'))
			->setClass('Kappa\ThemesManager\ThemeFactory');

		$builder->getDefinition('nette.templateFactory')
			->setClass('Kappa\ThemesManager\Template\TemplateFactory');

		$this->processThemesManager($builder, $config);
	}

	private function processThemesManager(ContainerBuilder &$builder, $config)
	{
		$globalConfig = null;
		$themesManager = $builder->addDefinition($this->prefix('themesManager'))
			->setClass('Kappa\ThemesManager\ThemesManager');

		if (isset($config['*'])) {
			$globalConfig = $config['*'];
			unset($config['*']);
		}

		foreach ($config as $themeName => $settings) {
			$settings = Helpers::merge($settings, $this->defaultSectionConfig);
			if ($globalConfig) {
				$settings = Helpers::merge($settings, $globalConfig);
			}
			foreach ($settings['helpers'] as $helperName => $helper) {
				$callback = explode('::', $helper);
				if (count($callback) != 2) {
					throw new InvalidArgumentException("Helper '{$helper}' has no correct format. Format must be '@service::publicMethod'");
				}
				$settings['helpers'][$helperName] = $callback;
			}
			$templateConfigurator = new Statement('Kappa\ThemesManager\Template\TemplateConfigurator', [
				$settings['params'],
				$settings['helpers'],
				$settings['macros']
			]);
			$pathMasksProvider = new Statement('Kappa\ThemesManager\Mapping\PathMasksProvider', [
				$settings['pathMasks']
			]);

			$theme = new Statement('Kappa\ThemesManager\Theme', [
				$this->prefix('@formatter'),
				$templateConfigurator,
				$pathMasksProvider,
				$themeName
			]);

			$themesManager->addSetup('addTheme', [$theme]);
		}
	}
}