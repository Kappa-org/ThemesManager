<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\ThemesManager\Tests;

use Kappa\ThemesManager\ThemeRegistry;
use KappaTests\ThemesManager\Tests\Mocks\TestHelper;
use Nette\Application\Application;
use Nette\Configurator;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ThemesManagerExtensionTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ThemesManagerExtensionTest extends TestCase
{
	public function testPathMapperFactory()
	{
		$type = 'Kappa\ThemesManager\Mapping\PathMapperFactory';
		Assert::type($type, $this->getContainer()->getByType($type));
	}

	public function testThemeRegistry()
	{
		$type = 'Kappa\ThemesManager\ThemeRegistry';
		/** @var ThemeRegistry $service */
		$service = $this->getContainer()->getByType($type);
		Assert::type($type, $service);
		$theme = $service->getTheme('foo');
		Assert::type('Kappa\ThemesManager\Theme', $theme);
		Assert::same('foo', $theme->getName());
		Assert::same(DATA_DIR, $theme->getThemeDir());
		Assert::equal(['test'], $theme->getPathMapper()->getFormatTemplateFiles());
		Assert::count(3, $theme->getTemplateConfigurator()->getParameters());
		Assert::equal(['helper' => [new TestHelper(), 'process']], $theme->getTemplateConfigurator()->getHelpers());
	}

	public function testCustomTemplateFactory()
	{
		Assert::type('Kappa\ThemesManager\Template\TemplateFactory', $this->getContainer()->getService('nette.templateFactory'));
	}

	/**
	 * @return \Nette\DI\Container
	 */
	private function getContainer()
	{
		$configurator = new Configurator();
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../../../data/config.neon');

		return $configurator->createContainer();
	}
}

\run(new ThemesManagerExtensionTest());
