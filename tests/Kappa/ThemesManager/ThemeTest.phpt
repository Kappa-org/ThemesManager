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
use Kappa\ThemesManager\Mapping\MaskType;
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Kappa\ThemesManager\Template\TemplateConfigurator;
use Kappa\ThemesManager\Theme;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThemeTest
 * @package Kappa\ThemesManager\Tests
 */
class ThemeTest extends MockTestCase
{
	/** @var \Kappa\ThemesManager\Theme */
	private $theme;

	protected function setUp()
	{
		parent::setUp();
		$templateConfigurator = new TemplateConfigurator(['themeDir' => __DIR__]);
		$pathMasksProvider = new PathMasksProvider([
			MaskType::PRESENTERS => [':themeName:'],
			MaskType::LAYOUTS => [':themeName:']
		]);
		$presenterMock = new \PresenterMock('Module1:Module2:MyPresenter', 'myAction', 'myView');
		$applicationMock = $this->mockista->create('Nette\Application\Application', [
			'getPresenter' => $presenterMock
		]);
		$formatter = new Formatter($applicationMock);
		$this->theme = new Theme($formatter, $templateConfigurator, $pathMasksProvider, 'myTheme');
	}

	public function testGetParameters()
	{
		Assert::equal(['themeDir' => __DIR__], $this->theme->getParameters());
	}

	public function testGetParameter()
	{
		Assert::same(__DIR__, $this->theme->getParameter('themeDir'));
		Assert::null($this->theme->getParameter('no'));
	}

	public function testGetName()
	{
		Assert::same('myTheme', $this->theme->getName());
	}

	public function testGetFormatLayoutTemplateFiles()
	{
		Assert::equal(['myTheme'], $this->theme->getFormatLayoutTemplateFiles());
	}

	public function testGetFormatTemplateFiles()
	{
		Assert::equal(['myTheme'], $this->theme->getFormatTemplateFiles());
	}
}

\run(new ThemeTest());