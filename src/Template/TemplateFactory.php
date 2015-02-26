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
use Nette\Application\UI;

/**
 * Class TemplateFactory
 *
 * @package Kappa\ThemesManager\Template
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TemplateFactory extends \Nette\Bridges\ApplicationLatte\TemplateFactory
{
	/** @var Theme */
	private $theme = null;

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
	 * @param UI\Control $control
	 * @return \Nette\Bridges\ApplicationLatte\Template
	 */
	public function createTemplate(UI\Control $control = null)
	{
		$template = parent::createTemplate($control);
		if ($this->theme) {
			$template = $this->theme->getTemplateConfigurator()->configureTemplate($template);
		}

		return $template;
	}
}
