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

use Kappa\ThemesManager\Mapping\Formatter;
use Kappa\ThemesManager\Mapping\MaskType;
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Kappa\ThemesManager\Template\TemplateConfigurator;
use Nette\Application\UI\ITemplate;
use Nette\Object;

/**
 * Class Theme
 * @package Kappa\ThemesManager
 */
class Theme extends Object
{
	/** @var \Kappa\ThemesManager\Mapping\Formatter */
	private $formatter;

	/** @var \Kappa\ThemesManager\Template\TemplateConfigurator */
	private $templateConfigurator;

	/** @var \Kappa\ThemesManager\Mapping\PathMasksProvider */
	private $pathMaskProvider;

	/** @var string */
	private $name;

	/**
	 * @param Formatter $formatter
	 * @param TemplateConfigurator $templateConfigurator
	 * @param PathMasksProvider $pathMaskProvider
	 * @param string $name
	 */
	public function __construct(Formatter $formatter, TemplateConfigurator $templateConfigurator, PathMasksProvider $pathMaskProvider, $name)
	{
		$this->formatter = $formatter;
		$this->templateConfigurator = $templateConfigurator;
		$this->pathMaskProvider = $pathMaskProvider;
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->templateConfigurator->getParameters();
	}

	/**
	 * @param string $name
	 * @return mixed|null
	 */
	public function getParameter($name)
	{
		return $this->templateConfigurator->getParameter($name);
	}

	/**
	 * @return array
	 */
	public function getFormatLayoutTemplateFiles()
	{
		$masks = $this->pathMaskProvider->getMasks(MaskType::LAYOUTS);

		return $this->formatter->getFormattedPaths($masks, $this->getParameter('themeDir'), $this->getName());
	}

	/**
	 * @return array
	 */
	public function getFormatTemplateFiles()
	{
		$masks = $this->pathMaskProvider->getMasks(MaskType::PRESENTERS);

		return $this->formatter->getFormattedPaths($masks, $this->getParameter('themeDir'), $this->getName());
	}

	/**
	 * @param ITemplate $template
	 */
	public function configureTemplate(ITemplate $template)
	{
		return $this->templateConfigurator->configureTemplate($template);
	}
} 