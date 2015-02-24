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
use Kappa\ThemesManager\InvalidStateException;
use Kappa\ThemesManager\Theme;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;

/**
 * Class PathMapper
 *
 * @package Kappa\ThemesManager\Mapping
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class PathMapper
{
	/** @var Application */
	private $application;

	/** @var Theme */
	private $theme;

	/** @var PathMasksProvider */
	private $pathMasksProvider;

	/**
	 * @param Application $application
	 * @param PathMasksProvider $pathMasksProvider
	 * @param Theme $theme
	 */
	public function __construct(Application $application, PathMasksProvider $pathMasksProvider, Theme $theme)
	{
		$this->application = $application;
		$this->pathMasksProvider = $pathMasksProvider;
		$this->theme = $theme;
	}

	/**
	 * @return array
	 */
	public function getFormatLayoutTemplateFiles()
	{
		return $this->getFormattedPaths(PathMasksProvider::LAYOUTS);
	}

	/**
	 * @return array
	 */
	public function getFormatTemplateFiles()
	{
		return $this->getFormattedPaths(PathMasksProvider::TEMPLATES);
	}

	/**
	 * @param string $type
	 * @return array
	 */
	private function getFormattedPaths($type)
	{
		$masks = $this->pathMasksProvider->getMasks($type);

		return $this->format($masks);
	}

	/**
	 * @param array $masks
	 * @return array
	 */
	private function format(array $masks)
	{
		foreach ($masks as $index => $mask) {
			$masks[$index] = $this->formatMask($mask);
		}

		return $masks;
	}

	/**
	 * @param string $mask
	 * @return string
	 */
	private function formatMask($mask)
	{
		$basicPlaceholders = [
			':themeName:' => $this->theme->getName(),
			':themeDir:' => $this->theme->getThemeDir(),
			':action:' => $this->getPresenter()->getAction(),
			':view:' => $this->getPresenter()->getView(),
			':presenter:' => $this->getPresenterName($this->getPresenter())
		];
		$mask = str_replace(array_keys($basicPlaceholders), array_values($basicPlaceholders), $mask);
		$moduleName = $this->getModuleName($this->getPresenter());
		if (strpos($mask, ':module') !== false) {
			if (!$moduleName) {
				throw new InvalidArgumentException("Missing module in '{$this->getPresenter()->getName()}' presenter for parse path mask '{$mask}'");
			}
			$modulePath = str_replace(':', '/', $moduleName);
			$mask = str_replace(':modules:', $modulePath, $mask);
			if (preg_match_all('~:module_([0-9]+):~', $mask, $matches)) {
				$expand = explode(':', $moduleName);
				foreach ($matches[0] as $key => $pattern) {
					$mask = str_replace($pattern, $expand[$matches[1][$key] - 1], $mask);
				}
			}
		}

		return $mask;
	}

	/**
	 * @return \Nette\Application\IPresenter
	 */
	private function getPresenter()
	{
		$presenter = $this->application->getPresenter();
		if (!$presenter) {
			throw new InvalidStateException("Missing presenter");
		}
		
		return $presenter;
	}

	/**
	 * @param Presenter $presenter
	 * @return string
	 */
	private function getPresenterName(Presenter $presenter)
	{
		$presenterName = $presenter->getName();
		if (strpos($presenterName, ':')) {
			$presenterName = substr(strrchr($presenter->getName(), ':'), 1);
		}

		return $presenterName;
	}

	/**
	 * @param Presenter $presenter
	 * @return null|string
	 */
	private function getModuleName(Presenter $presenter)
	{
		$module = null;
		if (strpos($presenter->getName(), ':')) {
			$presenterName = $this->getPresenterName($presenter);
			$module = substr($presenter->getName(), 0, (strlen($presenterName) + 1) * -1);
		}

		return $module;
	}
}
