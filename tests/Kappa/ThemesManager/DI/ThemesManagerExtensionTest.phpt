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

use Kappa\Tester\TestCase;
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ThemesManagerExtensionTest
 * @package Kappa\ThemesManager\Tests
 */
class ThemesManagerExtensionTest extends TestCase
{
	/** @var \Nette\DI\Container */
	private $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function testFormatter()
	{
		$service = $this->container->getByType('Kappa\ThemesManager\Mapping\Formatter');
		Assert::type('Kappa\ThemesManager\Mapping\Formatter', $service);
	}

	public function testThemeManager()
	{
		$service = $this->container->getByType('Kappa\ThemesManager\ThemesManager');
		Assert::type('Kappa\ThemesManager\ThemesManager', $service);
		Assert::type('Kappa\ThemesManager\Theme', $service->getTheme('section'));
	}
}

\run(new ThemesManagerExtensionTest(getContainer()));