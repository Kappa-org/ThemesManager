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
use Kappa\ThemesManager\Mapping\PathMasksProvider;
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
		Assert::type('Kappa\ThemesManager\Mapping\PathMasksProvider', new PathMasksProvider($masks));
		Assert::throws(function () {
			new PathMasksProvider([]);
		}, 'Kappa\ThemesManager\InvalidArgumentException');
	}

	public function testGetMasks()
	{
		$masks = [
			MaskType::PRESENTERS => ['presenters'],
			MaskType::LAYOUTS => ['layouts']
		];
		$pathMaskProvider = new PathMasksProvider($masks);
		Assert::equal($masks[MaskType::PRESENTERS], $pathMaskProvider->getMasks(MaskType::PRESENTERS));
		Assert::equal($masks[MaskType::LAYOUTS], $pathMaskProvider->getMasks(MaskType::LAYOUTS));
	}
}

\run(new PathMasksProviderTest());