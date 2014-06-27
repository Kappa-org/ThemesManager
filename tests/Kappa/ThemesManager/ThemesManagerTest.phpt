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
use Kappa\ThemesManager\Theme;
use Kappa\ThemesManager\ThemesManager;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThemesManagerTest
 * @package Kappa\ThemesManager\Tests
 */
class ThemesManagerTest extends MockTestCase
{
	/** @var \Kappa\ThemesManager\ThemesManager */
	private $themesManager;

	protected function setUp()
	{
		parent::setUp();
		$this->themesManager = new ThemesManager();
	}

	public function testTheme()
	{
		$formater = $this->mockista->create('Kappa\ThemesManager\Mapping\Formatter');
		$templateConfigurator = $this->mockista->create('Kappa\ThemesManager\Template\TemplateConfigurator');
		$pathMasksProvider = $this->mockista->create('Kappa\ThemesManager\Mapping\PathMaskProvider');
		$theme = new Theme($formater, $templateConfigurator, $pathMasksProvider, 'myTheme');
		Assert::type(get_class($this->themesManager), $this->themesManager->addTheme($theme));
		Assert::equal($theme, $this->themesManager->getTheme('myTheme'));
	}
}

\run(new ThemesManagerTest());