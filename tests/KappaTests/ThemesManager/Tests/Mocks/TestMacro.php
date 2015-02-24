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

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * Class TestMacro
 *
 * @package KappaTests\ThemesManager\Tests\Mocks
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class TestMacro extends MacroSet
{
	/**
	 * @param Compiler $compiler
	 */
	public static function install(Compiler $compiler)
	{
		$set = new static($compiler);
		$set->addMacro('id', NULL, NULL, array($set, 'macroId'));
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 * @return string
	 */
	public function macroId(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('if ($_l->tmp = array_filter(%node.array)) echo \' id="\' . %escape(implode(" ", array_unique($_l->tmp))) . \'"\'');
	}

}
