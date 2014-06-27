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
use Nette\Object;

/**
 * Class PathMasksProvider
 * @package Kappa\ThemesManager\Mapping
 */
class PathMasksProvider extends Object
{
	/** @var array */
	private $masks = [];

	/**
	 * @param array $masks
	 * @throws \Kappa\ThemesManager\InvalidArgumentException
	 */
	public function __construct(array $masks)
	{
		if (!$this->isValidMasks($masks)) {
			throw new InvalidArgumentException("Wrong masks. Masks must have 'presenters' and 'layouts' sections and must be array");
		}
		$this->masks = $masks;
	}

	/**
	 * @param string $type
	 * @return array
	 * @throws \Kappa\ThemesManager\InvalidArgumentException
	 */
	public function getMasks($type)
	{
		if ($type != MaskType::PRESENTERS && $type != MaskType::LAYOUTS) {
			throw new InvalidArgumentException("Mask type must be MaskType::PRESENTERS or MaskType::LAYOUTS");
		}

		return $this->masks[$type];
	}

	/**
	 * @param array $masks
	 * @return bool
	 */
	private function isValidMasks(array $masks)
	{
		if (!isset($masks[MaskType::LAYOUTS], $masks[MaskType::PRESENTERS])) {
			return false;
		}
		if (!is_array($masks[MaskType::LAYOUTS]) || !is_array($masks[MaskType::PRESENTERS])) {
			return false;
		}

		return true;
	}
}