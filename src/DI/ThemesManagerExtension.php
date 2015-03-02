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
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Helpers;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;

/**
 * Class ThemesManagerExtension
 *
 * @package Kappa\ThemesManager\DI
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ThemesManagerExtension extends CompilerExtension
{
	private $defaultConfig = [
		'documentRoot' => '%wwwDir%',
		'assetsDir' => 'assets',
		'themes' => []
	];

	private $defaultThemeConfig = [
		'themeDir' => null,
		'assetsDir' => null,
		'parameters' => [],
		'helpers' => [],
		'macros' => [],
		'pathMasks' => [
			PathMasksProvider::TEMPLATES => [],
			PathMasksProvider::LAYOUTS => []
		]
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('assetsResolver'))
			->setClass('Kappa\ThemesManager\Resolvers\AssetsPublisher', [
				$this->prefix('@themeRegistry'),
				$config['documentRoot'],
				$config['assetsDir']
			]);

		$builder->addDefinition($this->prefix('pathMapperFactory'))
			->setClass('Kappa\ThemesManager\Mapping\PathMapperFactory');

		$registry = $builder->addDefinition($this->prefix('themeRegistry'))
			->setClass('Kappa\ThemesManager\ThemeRegistry');

		if ($builder->hasDefinition('latte.templateFactory')) {
			$templateFactory = $builder->getDefinition('latte.templateFactory');
		} else {
			$templateFactory = $builder->getDefinition('nette.templateFactory');
		}
		$templateFactory->setFactory('Kappa\ThemesManager\Template\TemplateFactory');

		$themesConfig = $config['themes'];
		$defaultConfig = null;
		if (array_key_exists('*', $themesConfig)) {
			$defaultConfig = $themesConfig['*'];
			unset($themesConfig['*']);
		}
		foreach ($themesConfig as $name => $configuration) {
			$configuration = Helpers::merge($configuration, $this->defaultThemeConfig);
			if ($defaultConfig) {
				$configuration = Helpers::merge($configuration, $defaultConfig);
			}
			foreach ($configuration['helpers'] as $helperName => $helper) {
				$callback = explode('::', $helper);
				if (count($callback) != 2) {
					throw new InvalidArgumentException("Helper '{$helper}' has no correct format. Format must be '@service::publicMethod'");
				}
				$configuration['helpers'][$helperName] = $callback;
			}
			$templateConfigurator = new Statement('Kappa\ThemesManager\Template\TemplateConfigurator', [
				array_merge($configuration['parameters'], [
					'themeDir' => realpath($configuration['themeDir']),
					'assetsDir' => str_replace(':themeDir:', realpath($configuration['themeDir']), $configuration['assetsDir'])
				]),
				$configuration['helpers'],
				$configuration['macros']
			]);
			$pathMasksProvider = new Statement('Kappa\ThemesManager\Mapping\PathMasksProvider', [
				$configuration['pathMasks']
			]);
			$theme = new Statement('Kappa\ThemesManager\Theme', [
				$name,
				$templateConfigurator,
				$pathMasksProvider
			]);
			$registry->addSetup('addTheme', [$theme]);
		}
	}

	public function afterCompile(ClassType $class)
	{
		$builder = $this->getContainerBuilder();

		// metoda initialize
		$initialize = $class->methods['initialize'];
		$initialize->addBody('$this->getService(?)->resolve();', [$builder->getByType('Kappa\ThemesManager\Resolvers\AssetsPublisher')]);
	}
}
