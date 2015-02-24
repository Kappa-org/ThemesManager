<?php
/**
 * This file is part of the Kappa\ThemesManager package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace KappaTests\ThemesManager\Tests\Mocks;

/**
 * Class Application
 *
 * @package KappaTests\ThemesManager\Tests\Mocks
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class ApplicationMock extends \Nette\Application\Application
{
	public function getPresenter()
	{
		return new PresenterMock();
	}
}
