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

use Kappa\ThemesManager\Theme;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;

/**
 * Class TemplateFactory
 * @package Kappa\ThemesManager\Template
 */
class TemplateFactory extends \Nette\Bridges\ApplicationLatte\TemplateFactory
{
	/** @var \Kappa\ThemesManager\Theme */
	private $theme;

	/**
	 * @param Theme $theme
	 * @return $this
	 */
	public function setTheme(Theme $theme)
	{
		$this->theme = $theme;

		return $this;
	}

	/**
	 * @param Control $control
	 * @return \Nette\Application\UI\ITemplate|Template
	 */
	public function createTemplate(Control $control)
	{
		$template = parent::createTemplate($control);

		return $this->theme ? $this->theme->configureTemplate($template) : $template;
	}
} 