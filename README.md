# Kappa\ThemesManager

Package for easier work with themes

## Requirements:

* PHP 5.4 or higher
* Nette\DI 2.2 or higher

## Installation:

The best way to install Kappa\ThemesManager is using Composer

```sh
$ composer require kappa/application:@dev
```

## Usages

First you must register extension

```yaml
extensions:
	themes: Kappa\ThemesManager\DI\ThemesManagerExtension
```

Now you can configure templates for your application in config file

```yaml
themes:
	themeName:
		helpers:
			# helpers in format helperName: @service::method
		macros:
			# macros
		pathMasks:
			presenters:
				# path masks for formatTemplateFiles
			layouts:
				# path masks for formatLayoutTemplateFiles
		params:
			themeDir: %wwwDir%/../../ # this is only required item
			# and any next params
```

All settings will be used only in own section.

In masks you can use next placeholders:

* `:themeDir:` - contains theme dir path
* `:presenter:` - contains presenter name (without modules)
* `:modules:` - contains module name (module name My:Module will be replaced to My/Module)
* `:module_(number):` - contains module name (My:Module => module_1 = My, module_2 => Module)
* `:action:` - contains presenter acion name
* `:view:` - contains presenter view name
* `:themeName:` - will be replaced for theme name

In your presenter you can get template factory and file formats

Usages [kdyby/autowired](https://github.com/Kdyby/Autowired/)
```php
class BasePresenter extends Presenter
{
	use AutowireProperties;

	/**
	 * @var \Kappa\ThemesManager\Theme
	 * @autowire(admin, factory=\Kappa\ThemesManager\ThemeFactory)
	 */
	public $theme;

```

or classic
```php
class BasePresenter extends Presenter
{
	/** @var \Kappa\ThemesManager\ThemesManager @inject */
	public $themesManager;

	/** @var \Kappa\ThemesManager\Theme */
	protected $theme;

	protected function setUp()
	{
		parent::setUp();
		$this->theme = $this->themesManager->getTheme('admin');
	}
```

Update template:

```php
protected function createTemplate()
{
	$template = parent::createTemplate();
	$template = $this->theme->configureTemplate($template);

    return $template;
}
```

now your presenter have macros, helpers and params from section 'themeName' defined in config file

Next you can use custom path masks. Example:

```php
public function formatLayoutTemplateFiles()
{
    $list = $this->theme->getFormatLayoutTemplateFiles();

    return $list;
}
```

```php
public function formatTemplateFiles()
{
    $list = $this->theme->getFormatTemplateFiles();

    return $list;
}
```