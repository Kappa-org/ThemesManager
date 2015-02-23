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
 * Class ThemesManager
 * @package Kappa\ThemesManager
 */
class ThemesManager extends Object
{
	/** @var array */
	private $themes = [];

	/**
	 * @param Theme $theme
	 * @return $this
	 */
	public function addTheme(Theme $theme)
	{
		$this->themes[$theme->getName()] = $theme;

		return $this;
	}

	/**
	 * @param string $name
	 * @return null|Theme
	 */
	public function getTheme($name)
	{
		return isset($this->themes[$name]) ? $this->themes[$name] : null;
	}
} 