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

use Kappa\ThemesManager\Mapping\PathMapper;
use Kappa\ThemesManager\Mapping\PathMapperFactory;
use Kappa\ThemesManager\Mapping\PathMasksProvider;
use Kappa\ThemesManager\Template\TemplateConfigurator;

/**
 * Class Theme
 *
 * @package Kappa\ThemesManager
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class Theme
{
	/** @var string */
	private $name;

	/** @var string */
	private $themeDir;

	/** @var TemplateConfigurator */
	private $templateConfigurator;

	/** @var PathMapperFactory */
	private $pathMapperFactory;

	/** @var PathMasksProvider */
	private $masksProvider;

	/**
	 * @param string $name
	 * @param string $themeDir
	 * @param TemplateConfigurator $templateConfigurator
	 * @param PathMasksProvider $masksProvider
	 * @param PathMapperFactory $pathMapperFactory
	 */
	public function __construct($name, $themeDir, TemplateConfigurator $templateConfigurator, PathMasksProvider $masksProvider, PathMapperFactory $pathMapperFactory)
	{
		if (!file_exists($themeDir) || !is_readable($themeDir)) {
			throw new InvalidArgumentException("Theme dir '{$themeDir}' has not been found or readable");
		}
		$this->name = $name;
		$this->themeDir = $themeDir;
		$this->templateConfigurator = $templateConfigurator;
		$this->pathMapperFactory = $pathMapperFactory;
		$this->templateConfigurator->setParameter('themeDir', $this->themeDir);
		$this->masksProvider = $masksProvider;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getThemeDir()
	{
		return $this->themeDir;
	}

	/**
	 * @return TemplateConfigurator
	 */
	public function getTemplateConfigurator()
	{
		return $this->templateConfigurator;
	}

	/**
	 * @return PathMapper
	 */
	public function getPathMapper()
	{
		return $this->pathMapperFactory->create($this, $this->masksProvider);
	}
}
