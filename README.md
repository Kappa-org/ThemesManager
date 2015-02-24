# Kappa\ThemesManager

Package for easier work with themes

## Requirements:

* PHP 5.4 or higher
* [nette\di](https://github.com/nette/di) 2.2 or higher
* [nette\application](https://github.com/nette/application) 2.2 or higher

## Installation:

The best way to install Kappa\ThemesManager is using Composer

```sh
$ composer require kappa/themes-manager:@dev
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
		themeDir: %wwwDir%/../../ # this is only required item
		params:
			# params
		helpers:
			# helpers in format helperName: @service::method
		macros:
			# macros
		pathMasks:
			templates:
				# path masks for formatTemplateFiles
			layouts:
				# path masks for formatLayoutTemplateFiles
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

Usages [kdyby/autowired](https://github.com/Kdyby/Autowired/), **it is recommended**
```php
class BasePresenter extends Presenter
{
	use AutowireProperties;

	/**
	 * @var \Kappa\ThemesManager\Theme
	 * @autowire(admin, factory=\Kappa\ThemesManager\ThemeRegistry)
	 */
	public $theme;

```

or classic
```php
class BasePresenter extends Presenter
{
	/** @var \Kappa\ThemesManager\ThemesManager @inject */
	public $themesManager;
```

Update template:

```php
public function getTemplateFactory()
{
	$templateFactory = parent::createTemplate();
	// For kdyby/autowired
	$templateFactory->setTheme($this->theme);
	// Else
	$theme = $this->themesManager->getTheme('admin');
	$templateFactory->setTheme($theme);

    return $template;
}
```

now your presenter have macros, helpers and params from section 'themeName' defined in config file

Next you can use custom path masks. Example:

```php
public function formatLayoutTemplateFiles()
{
    $list = $this->theme->getPathMapper->getFormatLayoutTemplateFiles();

    return $list;
}

public function formatTemplateFiles()
{
    $list = $this->theme->getPathMapper()->getFormatTemplateFiles();

    return $list;
}
```
