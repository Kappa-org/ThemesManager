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
use Nette\Application\Application;
use Nette\Application\UI\Presenter;
use Nette\Object;

/**
 * Class Formatter
 * @package Kappa\ThemesManager\Mapping
 */
class Formatter extends Object
{
	/** @var \Nette\Application\Application */
	private $application;

	/**
	 * @param Application $application
	 */
	public function __construct(Application $application)
	{
		$this->application = $application;
	}

	/**
	 * @param array $masks
	 * @param string $themeDir
	 * @param string $sectionName
	 * @return array
	 */
	public function getFormattedPaths(array $masks, $themeDir, $sectionName)
	{
		foreach ($masks as $key => $mask) {
			$masks[$key] = $this->parseMask($mask, $themeDir, $sectionName);
		}

		return $masks;
	}

	/**
	 * @param string $mask
	 * @param string $themeDir
	 * @param string $sectionName
	 * @return string
	 * @throws \Kappa\ThemesManager\InvalidArgumentException
	 */
	private function parseMask($mask, $themeDir, $sectionName)
	{
		$presenter = $this->application->getPresenter();
		$moduleName = $this->getModuleName($presenter);
		$replace = [
			':section:' => $sectionName,
			':themeDir:' => $themeDir,
			':view:' => $presenter->getView(),
			':presenter:' => $this->getPresenterName($presenter),
			':action:' => $presenter->getAction()
		];
		$mask = str_replace(array_keys($replace), array_values($replace), $mask);
		if (strpos($mask, ':module') !== false) {
			if (!$moduleName) {
				throw new InvalidArgumentException("Missing module in '{$presenter->getName()}' presenter for parse path mask '{$mask}'");
			}
			$modulePath = str_replace(':', '/', $moduleName);
			$mask = str_replace(':modules:', $modulePath, $mask);
			if (preg_match_all('~:module_([0-9]:)+~', $mask, $matches)) {
				$expand = explode(':', $moduleName);
				foreach ($matches[0] as $key => $pattern) {
					$mask = str_replace($pattern, $expand[$matches[1][$key] - 1], $mask);
				}
			}
		}

		return $mask;
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