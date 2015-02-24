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

use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Kappa\ThemesManager\Theme;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThemeTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ThemeTest extends TestCase
{
	/** @var Theme */
	private $theme;

	protected function setUp()
	{
		$configuratorMock = \Mockery::mock('alias:Kappa\ThemesManager\Template\TemplateConfigurator');
		$configuratorMock->shouldReceive('setParameter')->andReturnSelf();
		$pathMapperFactoryMock = \Mockery::mock('Kappa\ThemesManager\Mapping\PathMapperFactory');
		$pathMapperFactoryMock->shouldReceive('create')->andReturn(\Mockery::mock('Kappa\ThemesManager\Mapping\PathMapper'));
		$this->theme = new Theme('foo', __DIR__, $configuratorMock, new PathMasksProvider([]), $pathMapperFactoryMock);
	}

	public function testGetName()
	{
		Assert::same('foo', $this->theme->getName());
	}

	public function testGetThemeDir()
	{
		Assert::same(__DIR__, $this->theme->getThemeDir());
	}

	public function testGetPathMapper()
	{
		Assert::type('Kappa\ThemesManager\Mapping\PathMapper', $this->theme->getPathMapper());
	}

	public function getTemplateConfigurator()
	{
		Assert::type('Kappa\ThemesManager\Template\TemplateConfigurator', $this->theme->getTemplateConfigurator());
	}
}

\run(new ThemeTest());
