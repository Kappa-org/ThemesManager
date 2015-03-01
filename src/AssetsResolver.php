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

use Nette\Utils\FileSystem;
use Nette\Utils\Finder;

/**
 * Class AssetsResolver
 *
 * @package Kappa\ThemesManager
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class AssetsResolver
{
	/** @var ThemeRegistry */
	private $themeRegistry;

	/** @var string */
	private $wwwDir;

	/** @var string */
	private $targetDir;

	/**
	 * @param ThemeRegistry $themeRegistry
	 * @param string $wwwDir
	 * @param string $targetDir
	 */
	public function __construct(ThemeRegistry $themeRegistry, $wwwDir, $targetDir)
	{
		$this->themeRegistry = $themeRegistry;
		$this->wwwDir = $wwwDir;
		$this->targetDir = $targetDir;
	}

	public function resolve()
	{
		/** @var Theme $theme */
		foreach ($this->themeRegistry->getThemes() as $theme) {
			$this->resolveTheme($theme);
		}
	}

	/**
	 * @param Theme $theme
	 */
	private function resolveTheme(Theme $theme)
	{
		$assetsDir = $theme->getAssetsDir();
		$checksum = '';
		/** @var \SplFileInfo $file */
		foreach (Finder::findFiles('*')->from($assetsDir) as $path => $file) {
			$checksum = md5($file->getPathname(). $file->getMTime() . $checksum);
		}
		$dirName = $theme->getName() . '_' . $checksum;
		$targetDirectory = $this->wwwDir . DIRECTORY_SEPARATOR . $this->targetDir;
		if (!file_exists($targetDirectory)) {
			FileSystem::createDir($targetDirectory);
		}
		$directory = $targetDirectory . DIRECTORY_SEPARATOR . $dirName;
		if (!file_exists($directory)) {
			foreach (Finder::findDirectories($theme->getName() . '_*')->in($targetDirectory) as $old) {
				FileSystem::delete($old);
			}
			FileSystem::copy($assetsDir, $directory);
		}
		$theme->getTemplateConfigurator()->setParameter('assetsDir', $directory);
	}
}
