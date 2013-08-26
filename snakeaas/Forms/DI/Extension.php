<?php
/**
 * @author Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 25.8.13 11:45
 */

namespace snakeaas\Forms\DI;

use Nette\Forms\Container;
use snakeaas\Forms\Controls\CKEditor;


class Extension {

	public static function register($wwwDir) {
		Container::extensionMethod('addCKEditor', function (Container $container, $name, $label = NULL, $rows = NULL, $cols = NULL) use ($wwwDir) {
			return $container[$name] = new CKEditor($label, $rows, $cols, $wwwDir);
		});
	}

} 