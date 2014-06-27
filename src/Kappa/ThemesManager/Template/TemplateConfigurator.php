<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager\Template;

use Kappa\ThemesManager\InvalidArgumentException;
use Nette\Object;

/**
 * Class TemplateConfigurator
 * @package Kappa\ThemesManager\Template
 */
class TemplateConfigurator extends Object
{
	/** @var array */
	private $helpers = [];

	/** @var array */
	private $macros = [];

	/** @var array */
	private $parameters = [];

	/**
	 * @param array $parameters
	 * @param array $helpers
	 * @param array $macros
	 * @throws \Kappa\ThemesManager\InvalidArgumentException
	 */
	public function __construct(array $parameters, array $helpers = [], array $macros = [])
	{
		if (!isset($parameters['themeDir']) || !file_exists($parameters['themeDir'])) {
			throw new InvalidArgumentException("Missing 'themeDir' parameter or path has not been found");
		}
		$this->parameters = $parameters;
		$this->helpers = $helpers;
		$this->macros = $macros;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param string $name
	 * @return mixed|null
	 */
	public function getParameter($name)
	{
		return isset($this->parameters[$name]) ? $this->parameters[$name] : null;
	}
} 