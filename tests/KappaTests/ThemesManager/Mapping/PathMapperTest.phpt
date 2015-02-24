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

namespace KappaTest\ThemesManager\Mapping;

use Kappa\ThemesManager\Mapping\PathMapper;
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class PathMapperTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMapperTest extends TestCase
{
	/** @var PathMapper */
	private $pathMapper;

	protected function setUp()
	{
		$presenterMock = \Mockery::mock('Nette\Application\UI\Presenter');
		$presenterMock->shouldReceive('getName')->andReturn('Module1:Module2:Module3:Module4:Module5:Module6:Module7:Module8:Module9:Module10:FooPresenter');
		$presenterMock->shouldReceive('getAction')->andReturn('fooAction');
		$presenterMock->shouldReceive('getView')->andReturn('fooView');
		$applicationMock = \Mockery::mock('Nette\Application\Application');
		$applicationMock->shouldReceive('getPresenter')->andReturn($presenterMock);
		$pathMasksProvider = new PathMasksProvider([
			'fooTheme' => [
				PathMasksProvider::TEMPLATES => [
					':themeName:',
					':themeDir:',
					':view:',
					':action:',
					':presenter:',
					':modules:',
					':module_10:',
					':module_1:',
					'foo/:themeName:/:themeDir:/:view:/:action:/:presenter:/:modules:/:module_10:/:module_1:/bar'
				]
			]
		]);
		$themeMock = \Mockery::mock('Kappa\ThemesManager\Theme');
		$themeMock->shouldReceive('getName')->andReturn('fooTheme');
		$themeMock->shouldReceive('getThemeDir')->andReturn('fooDir');
		$this->pathMapper = new PathMapper($applicationMock, $pathMasksProvider, $themeMock);
	}

	public function testFormat()
	{
		$expected = [
			'fooTheme',
			'fooDir',
			'fooView',
			'fooAction',
			'FooPresenter',
			'Module1/Module2/Module3/Module4/Module5/Module6/Module7/Module8/Module9/Module10',
			'Module10',
			'Module1',
			'foo/fooTheme/fooDir/fooView/fooAction/FooPresenter/Module1/Module2/Module3/Module4/Module5/Module6/Module7/Module8/Module9/Module10/Module10/Module1/bar'
		];
		Assert::equal($expected, $this->pathMapper->getFormatTemplateFiles());
	}
}

\run(new PathMapperTest());
