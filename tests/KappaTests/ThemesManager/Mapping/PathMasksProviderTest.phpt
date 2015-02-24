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

namespace KappaTests\ThemesManager\Mapping;

use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Tester\TestCase;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class PathMasksProvider
 *
 * @package KappaTests\ThemesManager\Mapping
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMasksProviderTest extends TestCase
{
	public function testGetMasks()
	{
		$provider = new PathMasksProvider([
			'foo' => [
				PathMasksProvider::LAYOUTS => ['foo'],
				PathMasksProvider::TEMPLATES => ['bar'],
			]
		]);
		Assert::equal(['foo'], $provider->getMasks('foo', PathMasksProvider::LAYOUTS));
		Assert::equal(['bar'], $provider->getMasks('foo', PathMasksProvider::TEMPLATES));
		Assert::exception(function () use ($provider) {
			$provider->getMasks('foo', 'foo');
		}, 'Kappa\ThemesManager\InvalidArgumentException');
		Assert::exception(function () use ($provider) {
			$provider->getMasks('bar', 'foo');
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}
}

\run(new PathMasksProviderTest());
