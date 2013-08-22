<?php
/**
 * @author Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 21.8.13 14:50
 */

namespace snakeaas\NetteCKEditor\Html;


use DOMDocument;

class Html {

	/**
	 * @var DOMDocument
	 */
	protected $dom;


	/**
	 * @param string $html
	 */
	public function setHtml($html) {
		$this->dom = new DOMDocument();
		
		// additional div, because DOM adding automaticly doctype, html, head and body
		$this->dom->loadHTML('<div>' . $html . '</div>');
	}


	public function getImages() {
		$return = array();
		foreach ($this->dom->getElementsByTagName('img') as $img) {
			$return[] = new Image($img);
		}
		return $return;
	}


	/**
	 * @return string
	 */
	public function getHtml() {
		// write only added div (above) with content, and strip div
		return substr($this->dom->saveXML($this->dom->getElementsByTagName('div')->item(0)), 5, -6);
	}

} 