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
use Nette\Application\UI\ITemplateFactory;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\Loader;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\UIMacros;
use Nette\Bridges\CacheLatte\CacheMacro;
use Nette\Bridges\FormsLatte\FormMacros;
use Nette\Caching\IStorage;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Object;
use Nette\Security\User;

/**
 * Class TemplateFactory
 * @package Kappa\ThemesManager\Template
 */
class TemplateFactory extends Object implements ITemplateFactory
{
	/** @var \Nette\Bridges\ApplicationLatte\ILatteFactory */
	private $latteFactory;

	/** @var \Nette\Http\IRequest */
	private $httpRequest;

	/** @var \Nette\Http\IResponse */
	private $httpResponse;

	/** @var \Nette\Security\User */
	private $user;

	/** @var \Nette\Caching\IStorage */
	private $cacheStorage;

	/** @var \Kappa\ThemesManager\Theme */
	private $theme;

	/**
	 * @param ILatteFactory $latteFactory
	 * @param IRequest $httpRequest
	 * @param IResponse $httpResponse
	 * @param User $user
	 * @param IStorage $cacheStorage
	 */
	public function __construct(ILatteFactory $latteFactory, IRequest $httpRequest = null, IResponse $httpResponse = null, User $user = null, IStorage $cacheStorage = null)
	{
		$this->latteFactory = $latteFactory;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;
		$this->user = $user;
		$this->cacheStorage = $cacheStorage;
	}

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
		$latte = $this->latteFactory->create();
		$template = new Template($latte);
		$presenter = $control->getPresenter(false);

		if ($control instanceof Presenter) {
			$latte->setLoader(new Loader($control));
		}

		if ($latte->onCompile instanceof \Traversable) {
			$latte->onCompile = iterator_to_array($latte->onCompile);
		}

		array_unshift($latte->onCompile, function($latte) use ($control, $template) {
			$latte->getParser()->shortNoEscape = true;
			$latte->getCompiler()->addMacro('cache', new CacheMacro($latte->getCompiler()));
			UIMacros::install($latte->getCompiler());
			FormMacros::install($latte->getCompiler());
			$control->templatePrepareFilters($template);
		});

		$latte->addFilter('url', 'rawurlencode'); // back compatiblity
		foreach (array('normalize', 'toAscii', 'webalize', 'padLeft', 'padRight', 'reverse') as $name) {
			$latte->addFilter($name, 'Nette\Utils\Strings::' . $name);
		}

		// default parameters
		$template->control = $template->_control = $control;
		$template->presenter = $template->_presenter = $presenter;
		$template->user = $this->user;
		$template->netteHttpResponse = $this->httpResponse;
		$template->netteCacheStorage = $this->cacheStorage;
		$template->baseUri = $template->baseUrl = rtrim($this->httpRequest->getUrl()->getBaseUrl(), '/');
		$template->basePath = preg_replace('#https?://[^/]+#A', '', $template->baseUrl);
		$template->flashes = array();

		if ($presenter instanceof Presenter && $presenter->hasFlashSession()) {
			$id = $control->getParameterId('flash');
			$template->flashes = (array) $presenter->getFlashSession()->$id;
		}

		return $this->theme ? $this->theme->configureTemplate($template) : $template;
	}
} 