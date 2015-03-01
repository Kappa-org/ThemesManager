<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThemesManager\Resolvers;

use Kappa\ThemesManager\DirectoryNotFoundException;
use Kappa\ThemesManager\Theme;
use Kappa\ThemesManager\ThemeRegistry;
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
	private $documentRoot;

	/** @var string */
	private $targetDirectory;

	/**
	 * @param ThemeRegistry $themeRegistry
	 * @param string $documentRoot
	 * @param string $targetDirectory
	 */
	public function __construct(ThemeRegistry $themeRegistry, $documentRoot, $targetDirectory)
	{
		if (!is_dir($documentRoot)) {
			throw new DirectoryNotFoundException("Directory '{$documentRoot}' (document root) has not been found");
		}
		$this->themeRegistry = $themeRegistry;
		$this->documentRoot = $documentRoot;
		$this->targetDirectory = $targetDirectory;
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
		$checksum = $this->getDirectoryChecksum($theme->getAssetsDir());
		$path = "{$this->getTargetDirectory()}/{$theme->getName()}_{$checksum}";
		if (!file_exists($path)) {
			$this->dropOldAssets($theme->getName());
			FileSystem::copy($theme->getAssetsDir(), $path);
		}
		$relativePath = $this->makePathRelativeToRoot($path);
		$theme->getTemplateConfigurator()->setParameter('assetsDir', $relativePath);
	}

	/**
	 * @return string
	 */
	private function getTargetDirectory()
	{
		$path = $this->documentRoot . DIRECTORY_SEPARATOR . $this->targetDirectory;
		if (!file_exists($path)) {
			FileSystem::createDir($path);
		}

		return $path;
	}

	/**
	 * @param string $directory
	 * @return string
	 */
	private function getDirectoryChecksum($directory)
	{
		$checksum = '';
		/** @var \SplFileInfo $file */
		foreach (Finder::findFiles('*')->from($directory) as $file) {
			$checksum = md5($file->getPathname() . $file->getMTime() . $checksum);
		}

		return $checksum;
	}

	/**
	 * @param $themeName
	 */
	private function dropOldAssets($themeName)
	{
		/** @var \SplFileInfo $file */
		foreach (Finder::findDirectories($themeName . '_')->in($this->getTargetDirectory()) as $file) {
			FileSystem::delete($file->getPathname());
		}
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function makePathRelativeToRoot($path)
	{
		return str_replace($this->documentRoot, '', $path);
	}
}
