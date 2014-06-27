<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

/**
 * Class PresenterMock
 */
class PresenterMock extends \Nette\Application\UI\Presenter
{
	/** @var string */
	private $name;

	/** @var string */
	private $action;

	/**
	 * @param string $name
	 * @param string $action
	 * @param string $view
	 */
	public function __construct($name, $action, $view)
	{
		$this->name = $name;
		$this->action = $action;
		$this->setView($view);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param bool $fullyQualified
	 * @return string
	 */
	public function getAction($fullyQualified = FALSE)
	{
		return $this->action;
	}
}