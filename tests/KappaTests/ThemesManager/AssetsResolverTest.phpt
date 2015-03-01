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

use Kappa\ThemesManager\AssetsResolver;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class AssetsResolverTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class AssetsResolverTest extends TestCase
{
	/** @var AssetsResolver */
	private $assetsResolver;

	/** @var vfsStreamDirectory */
	private $wwwDir;

	protected function setUp()
	{
		$themeAssetsDir = DATA_DIR . '/assets';
		$this->wwwDir = vfsStream::setup('www');

		$templateConfigurator = \Mockery::mock('Kappa\ThemesManager\Template\TemplateConfigurator');
		$templateConfigurator->shouldReceive('setParameter')
			->with('assetsDir', \Mockery::any())
			->once()
			->andReturnSelf();
		$theme = \Mockery::mock('Kappa\ThemesManager\Theme');
		$theme->shouldReceive('getAssetsDir')->once()->withNoArgs()->andReturn($themeAssetsDir);
		$theme->shouldReceive('getName')->between(1, 2)->withNoArgs()->andReturn('foo');
		$theme->shouldReceive('getTemplateConfigurator')
			->once()
			->withNoArgs()
			->andReturn($templateConfigurator);
		$themeRegistry = \Mockery::mock('Kappa\ThemesManager\ThemeRegistry');
		$themeRegistry->shouldReceive('getThemes')->once()->withNoArgs()->andReturn([$theme]);
		$this->assetsResolver = new AssetsResolver($themeRegistry, vfsStream::url('www'), 'assets');
	}

	public function testResolve()
	{
		Assert::false(file_exists(vfsStream::url('www') . '/assets'));
		$this->assetsResolver->resolve();
		Assert::true(file_exists(vfsStream::url('www') . '/assets'));
		$assets = $this->wwwDir->getChild('assets')->getChildren();
		Assert::match('~foo_[a-z0-9]+~', $assets[0]->getName());
		Assert::true($assets[0]->hasChild('js'));
		$jsDir = $assets[0]->getChild('js');
		$jsFile = $jsDir->getChild('file.js');
		Assert::match('~^console.log\("Hello"\);\s*$~', $jsFile->getContent());
	}

	protected function tearDown()
	{
		\Mockery::close();
	}
}

\run(new AssetsResolverTest());
