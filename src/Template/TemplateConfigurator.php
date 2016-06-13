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
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Callback;

/**
 * Class TemplateConfigurator
 *
 * @package Kappa\ThemesManager\Template
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TemplateConfigurator
{
	/** @var array */
	private $helper = [];

	/** @var array */
	private $macros = [];

	/** @var array */
	private $parameters = [];

	/**
	 * @param array $parameters
	 * @param array $helpers
	 * @param array $macros
	 */
	public function __construct(array $parameters = [], $helpers = [], array $macros = [])
	{
		$this->helper = $helpers;
		$this->parameters = $parameters;
		$this->macros = $macros;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setParameter($name, $value)
	{
		$this->parameters[$name] = $value;

		return $this;
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
	 * @return mixed
	 */
	public function getParameter($name)
	{
		if (!array_key_exists($name, $this->parameters)) {
			throw new InvalidArgumentException(__METHOD__ . ": Missing '{$name}' parameter");
		}

		return $this->parameters[$name];
	}

	/**
	 * @return array
	 */
	public function getHelpers()
	{
		return $this->helper;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getHelper($name)
	{
		if (!array_key_exists($name, $this->helper)) {
			throw new InvalidArgumentException(__METHOD__ . ": Missing '{$name}' helper");
		}

		return $this->helper[$name];
	}

	/**
	 * @return array
	 */
	public function getMacros()
	{
		return $this->macros;
	}

	/**
	 * @param Template $template
	 * @return Template
	 */
	public function configureTemplate(Template $template)
	{
		foreach ($this->getParameters() as $name => $value) {
			$value = str_replace(":themeDir:", $this->getParameter('themeDir'), $value);
			$template->add($name, $value);
		}
		foreach ($this->getHelpers() as $name => $helper) {
			$template->addFilter($name, $helper);
		}
		foreach ($this->getMacros() as $macro) {
			Callback::invokeArgs([$macro, 'install'], [$template->getLatte()->getCompiler()]);
		}

		return $template;
	}
}
