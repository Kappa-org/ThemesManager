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
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThemeRegistryTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ThemeRegistryTest extends TestCase
{
	/** @var ThemeRegistry */
	private $registry;

	protected function setUp()
	{
		$this->registry = new ThemeRegistry();
	}

	public function testSetGetTheme()
	{
		$theme = \Mockery::mock('Kappa\ThemesManager\Theme');
		$theme->shouldReceive('getName')->andReturn('foo');
		Assert::type('Kappa\ThemesManager\ThemeRegistry', $this->registry->addTheme($theme));
		Assert::equal($theme, $this->registry->getTheme('foo'));
	}

	public function testSetCreate()
	{
		$theme = \Mockery::mock('Kappa\ThemesManager\Theme');
		$theme->shouldReceive('getName')->andReturn('foo');
		Assert::type('Kappa\ThemesManager\ThemeRegistry', $this->registry->addTheme($theme));
		Assert::equal($theme, $this->registry->create('foo'));
	}
}

\run(new ThemeRegistryTest());
