<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager\Mapping;

use Kappa\ThemesManager\Theme;
use Nette\Application\Application;

/**
 * Class PathMapperFactory
 *
 * @package Kappa\ThemesManager\Mapping
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMapperFactory
{
	/** @var Application */
	private $application;

	/** @var PathMasksProvider */
	private $pathMasksProvider;

	/**
	 * @param Application $application
	 * @param PathMasksProvider $pathMasksProvider
	 */
	public function __construct(Application $application, PathMasksProvider $pathMasksProvider)
	{
		$this->application = $application;
		$this->pathMasksProvider = $pathMasksProvider;
	}

	/**
	 * @param Theme $theme
	 * @return PathMapper
	 */
	public function create(Theme $theme)
	{
		return new PathMapper($this->application, $this->pathMasksProvider, $theme);
	}
}
