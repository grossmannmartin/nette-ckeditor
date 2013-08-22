<?php
/**
 * @author Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 21.8.13 20:10
 */

namespace snakeaas\NetteCKEditor\Html;


use DOMNode;
use SplFileInfo;

class Image {

	protected $image;

	public function __construct(DOMNode $image) {
		$this->image = $image;
	}

	/*public function getDimensionedSrc($width, $height) {

		$srcOld = $this->image->getAttribute('src');

		$f = new SplFileInfo($srcOld);
		return new SplFileInfo($f->getPath() . '/' . $f->getBasename('.' . $f->getExtension()) . "[{$width}x{$height}]." . $f->getExtension());
	}*/


	public function setSrc($src) {
		$this->image->removeAttribute('src');
		$this->image->setAttribute('src', $src);
	}


	// TODO: Where there are no dimensions
	public function getDimensionsFromStyle() {
		$style = $this->image->getAttribute('style');

		preg_match('~^\s*.*height\s*:\s*(\d+)\s*px.*\s*$~iU', $style, $matches);
		$height = $matches[1];

		preg_match('~^\s*.*width\s*:\s*(\d+)\s*px.*\s*$~iU', $style, $matches);
		$width = $matches[1];

		return array($width, $height);
	}

	/**
	 * @return SplFileInfo
	 */
	public function getSrc() {
		return new SplFileInfo($this->image->getAttribute('src'));
	}
} 