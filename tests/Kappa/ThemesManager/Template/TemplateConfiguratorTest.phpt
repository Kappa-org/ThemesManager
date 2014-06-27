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
use Kappa\ThemesManager\Template\TemplateConfigurator;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class TemplateConfiguratorTest
 * @package Kappa\ThemesManager\Tests
 */
class TemplateConfiguratorTest extends TestCase
{
	public function testCreateInstance()
	{
		$templateConfigurator = new TemplateConfigurator(['themeDir' => __DIR__]);
		Assert::type('Kappa\ThemesManager\Template\TemplateConfigurator', $templateConfigurator);
		Assert::throws(function () {
			new TemplateConfigurator([]);
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}
}

\run(new TemplateConfiguratorTest());