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

/**
 * Class ThemeRegistry
 *
 * @package Kappa\ThemesManager
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ThemeRegistry
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
	 * @return Theme|null
	 */
	public function getTheme($name)
	{
		if (!array_key_exists($name, $this->themes)) {
			throw new InvalidArgumentException("Missing theme with '{$name}' name");
		}

		return $this->themes[$name];
	}
}
