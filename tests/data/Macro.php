<?php
/**
 * This file is part of the Kappa\TemplateFactory package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

class Macro extends \Latte\Macros\MacroSet
{
	public static function install(\Latte\Compiler $compiler)
	{
		$set = new static($compiler);
	}
} 