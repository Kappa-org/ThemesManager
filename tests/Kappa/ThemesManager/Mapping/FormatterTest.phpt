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

use Kappa\Tester\MockTestCase;
use Kappa\ThemesManager\Mapping\Formatter;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class FormatterTest
 * @package Kappa\ThemesManager\Tests
 */
class FormatterTest extends MockTestCase
{
	/** @var \Kappa\ThemesManager\Mapping\Formatter */
	private $formatter;

	protected function setUp()
	{
		parent::setUp();
		$presenterMock = new \PresenterMock('Module1:Module2:MyPresenter', 'myAction', 'myView');
		$applicationMock = $this->mockista->create('Nette\Application\Application', [
			'getPresenter' => $presenterMock
		]);
		$this->formatter = new Formatter($applicationMock);
	}

	public function testGetFormattedPaths()
	{
		$masks = [':themeName:', ':modules:', ':module_2:', ':presenter:', ':action:', ':view:', ':themeDir:'];
		$expected = ['mySection', 'Module1/Module2', 'Module2', 'MyPresenter', 'myAction', 'myView', 'theme/dir'];
		Assert::equal($expected, $this->formatter->getFormattedPaths($masks, 'theme/dir', 'mySection'));
	}
}

\run(new FormatterTest());