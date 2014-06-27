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
use Kappa\ThemesManager\Mapping\MaskType;
use Kappa\ThemesManager\Mapping\PathMaskProvider;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class PathMasksProviderTest
 * @package Kappa\ThemesManager\Tests
 */
class PathMasksProviderTest extends TestCase
{
	public function testCreateInstance()
	{
		$masks = [
			MaskType::PRESENTERS => ['presenters'],
			MaskType::LAYOUTS => ['layouts']
		];
		Assert::type('Kappa\ThemesManager\Mapping\PathMaskProvider', new PathMaskProvider($masks));
		Assert::throws(function () {
			new PathMaskProvider([]);
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}

	public function testGetMasks()
	{
		$masks = [
			MaskType::PRESENTERS => ['presenters'],
			MaskType::LAYOUTS => ['layouts']
		];
		$pathMaskProvider = new PathMaskProvider($masks);
		Assert::equal($masks[MaskType::PRESENTERS], $pathMaskProvider->getMasks(MaskType::PRESENTERS));
		Assert::equal($masks[MaskType::LAYOUTS], $pathMaskProvider->getMasks(MaskType::LAYOUTS));
	}
}

\run(new PathMasksProviderTest());