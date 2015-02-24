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

namespace KappaTests\ThemesManager\Template;

use Kappa\ThemesManager\Template\TemplateConfigurator;
use KappaTests\ThemesManager\Tests\Mocks\TestHelper;
use Latte\Engine;
use Latte\Parser;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Compiler;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class TemplateConfiguratorTest
 *
 * @package KappaTests\ThemesManager\Template
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TemplateConfiguratorTest extends TestCase
{
	/** @var TemplateConfigurator */
	private $configurator;

	/** @var array */
	private $params;

	/** @var array */
	private $macros;

	/** @var array */
	private $helpers;

	protected function setUp()
	{
		$this->params = [
			'foo' => 'bar'
		];
		$this->macros = [
			'KappaTests\ThemesManager\Tests\Mocks\TestMacro'
		];
		$this->helpers = [
			'testHelper' => [new TestHelper(), 'process']
		];
		$this->configurator = new TemplateConfigurator($this->params, $this->helpers, $this->macros);
	}

	public function testGetParameters()
	{
		Assert::equal($this->params, $this->configurator->getParameters());
	}

	public function testGetParameter()
	{
		Assert::same('bar', $this->configurator->getParameter('foo'));
		Assert::exception(function () {
			$this->configurator->getParameter('bar');
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}

	public function testGetHelpers()
	{
		Assert::equal($this->helpers, $this->configurator->getHelpers());
	}

	public function testGetHelper()
	{
		Assert::same($this->helpers['testHelper'], $this->configurator->getHelper('testHelper'));
		Assert::exception(function () {
			$this->configurator->getHelper('bar');
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}

	public function testGetMacros()
	{
		Assert::equal($this->macros, $this->configurator->getMacros());
	}

	public function testConfigureTemplate()
	{
		$template = new Template(new Engine());
		$countOfFilters = count($template->getLatte()->getFilters());
		Assert::equal([], $template->getParameters());
		$this->configurator->configureTemplate($template);
		Assert::equal($this->params, $template->getParameters());
		Assert::same($countOfFilters + 1, count($template->getLatte()->getFilters()));
	}
}

\run(new TemplateConfiguratorTest());
