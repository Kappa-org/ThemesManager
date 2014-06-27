<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager\DI;

use Nette\DI\CompilerExtension;

/**
 * Class ThemesManagerExtension
 * @package Kappa\ThemesManager\DI
 */
class ThemesManagerExtension extends CompilerExtension
{
	/** @var array */
	private $defaultSectionConfig = [
		'helpers' => [],
		'macros' => [],
		'params' => [
			'themeDir' => null
		],
		'pathMasks' => [
			'presenters' => [],
			'layouts' => []
		]
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();
		$globalConfig = null;

		if (isset($config['*'])) {
			$globalConfig = $config['*'];
			unset($config['*']);
		}
	}
}