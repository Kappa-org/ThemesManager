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

use Kappa\ThemesManager\Mapping\PathMapperFactory;
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Kappa\ThemesManager\Theme;
use KappaTests\ThemesManager\Tests\MockTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class PathMapperFactoryTest
 *
 * @package Kappa\ThemesManager\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMapperFactoryTest extends MockTestCase
{
	/** @var PathMapperFactory */
	private $pathMapperFactory;

	protected function setUp()
	{
		parent::setUp();
		$applicationMock = $this->mockista->create('Nette\Application\Application');
		$pathMasksProvider = new PathMasksProvider([]);
		$this->pathMapperFactory = new PathMapperFactory($applicationMock, $pathMasksProvider);
	}

	public function testCreate()
	{
		$themeMock = $this->mockista->create('Kappa\ThemesManager\Theme');
		Assert::type('Kappa\ThemesManager\Mapping\PathMapper', $this->pathMapperFactory->create($themeMock));
	}
}

\run(new PathMapperFactoryTest());
