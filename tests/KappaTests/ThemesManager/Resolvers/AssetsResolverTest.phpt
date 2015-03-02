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

namespace KappaTests\ThemesManager\Resolvers;

use Kappa\ThemesManager\Resolvers\AssetsPublisher;
use Kappa\ThemesManager\Template\TemplateConfigurator;
use Mockery\MockInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class AssetsResolverTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class AssetsResolverTest extends TestCase
{
	/** @var AssetsPublisher */
	private $assetsResolver;

	/** @var MockInterface */
	private $theme;

	/** @var vfsStreamDirectory */
	private $directory;

	protected function setUp()
	{
		$this->directory = vfsStream::setup('root', null, $this->getStructure());

		$this->theme = \Mockery::mock('Kappa\ThemesManager\Theme');
		$this->theme->shouldReceive('getAssetsDir')
			->withNoArgs()
			->andReturn(vfsStream::url('root/themes/foo/assets'));
		$this->theme->shouldReceive('getName')
			->withNoArgs()
			->andReturn('foo');
		$this->theme->shouldReceive('getTemplateConfigurator')
			->withNoArgs()
			->andReturn(new TemplateConfigurator(['assetsDir' => vfsStream::url('root/themes/foo/assets')]));

		$themeRegistry = \Mockery::mock('Kappa\ThemesManager\ThemeRegistry');
		$themeRegistry->shouldReceive('getThemes')->once()->withNoArgs()->andReturn([$this->theme]);

		$this->assetsResolver = new AssetsPublisher($themeRegistry, vfsStream::url('root/www'), 'assets');
	}

	public function testResolve()
	{
		$structure = vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure();
		Assert::notEqual($structure['root']['themes']['foo'], $this->getStructure()['www']);
		Assert::same(vfsStream::url('root/themes/foo/assets'), $this->theme->getTemplateConfigurator()->getParameter('assetsDir'));
		$this->assetsResolver->resolve();
		$structure = vfsStream::inspect(new vfsStreamStructureVisitor())->getStructure();
		$moduleAssetsDirName = $this->directory->getChild('www')->getChildren()[0]->getChildren()[0]->getName();
		Assert::equal($structure['root']['themes']['foo']['assets'], $structure['root']['www']['assets'][$moduleAssetsDirName]);
		Assert::same('/assets/' . $moduleAssetsDirName, $this->theme->getTemplateConfigurator()->getParameter('assetsDir'));
	}

	/**
	 * @return array
	 */
	private function getStructure()
	{
		return [
			'www' => [],
			'themes' => [
				'foo' => [
					'assets' => [
						'js' => [
							'js.js' => 'console.log("Hello");'
						]
					]
				]
			]
		];
	}

	protected function tearDown()
	{
		\Mockery::close();
	}
}

\run(new AssetsResolverTest());
