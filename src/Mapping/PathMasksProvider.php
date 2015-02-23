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

use Kappa\ThemesManager\InvalidArgumentException;

/**
 * Class PathProvider
 *
 * @package Kappa\ThemesManager\Mapping
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMasksProvider
{
	const LAYOUTS = 'layouts';

	const TEMPLATES = 'templates';

	/** @var array */
	private $masks;

	/**
	 * @param array $masks
	 */
	public function __construct(array $masks)
	{
		$this->masks = $masks;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function getMasks($type)
	{
		$types = [self::LAYOUTS, self::TEMPLATES];
		if (!in_array($type, $types)) {
			throw new InvalidArgumentException('Unsupported path type. You can use only LAYOUT or TEMPLATES type');
		}

		return $this->masks[$type];
	}
}
