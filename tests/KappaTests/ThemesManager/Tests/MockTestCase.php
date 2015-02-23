<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\ThemesManager\Tests;

use Mockista\Registry;
use Tester\TestCase;

/**
 * Class MockTestCase
 *
 * @package KappaTests\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class MockTestCase extends TestCase
{
	/** @var Registry */
	protected $mockista;

	protected function setUp()
	{
		$this->mockista = new Registry();
	}

	protected function tearDown()
	{
		$this->mockista->assertExpectations();
	}
}
