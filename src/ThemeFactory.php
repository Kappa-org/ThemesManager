<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager;

use Nette\Object;

/**
 * Class ThemeFactory
 * @package Kappa\ThemesManager
 */
class ThemeFactory extends Object
{
	/** @var \Kappa\ThemesManager\ThemesManager */
	private $themesManager;

	/**
	 * @param ThemesManager $themesManager
	 */
	public function __construct(ThemesManager $themesManager)
	{
		$this->themesManager = $themesManager;
	}

	/**
	 * @param string $themeName
	 * @return Theme
	 */
	public function create($themeName)
	{
		return $this->themesManager->getTheme($themeName);
	}
} 