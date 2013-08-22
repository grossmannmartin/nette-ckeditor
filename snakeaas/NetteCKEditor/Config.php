<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <grossmann.martin@ovacloud.net>
 * @created 20.8.13 12:30
 */

namespace snakeaas\NetteCKEditor;


use Nette\Object;

class Config extends Object {

	protected $data;


	public function __construct() {
		$this->data = array();
		$this->setDefaults();
	}


	public function setDefaults() {
		$this->data['toolbarGroups'] = array(
			array('name' => 'document', 'groups' => array('document', 'doctools')),
			array('name' => 'clipboard', 'groups' => array('clipboard', 'undo')),
			array('name' => 'styles'),
			array('name' => 'editing', 'groups' => array('find', 'selection')),
			array('name' => 'basicstyles', 'groups' => array('basicstyles')),
			array('name' => 'paragraph', 'groups' => array('blocks', 'align', 'list', 'indent')),
			'/',
			array('name' => 'links'),
			array('name' => 'insert'),
			array('name' => 'tools'),
			array('name' => 'cleanup'),
			array('name' => 'mode'),
			array('name' => 'others'),
			array('name' => 'about'),
		);

		$this->data['removeButtons'] = 'Cut,Copy,Paste,Styles,Anchor,Blockquote,Redo,Underline,Strike';
		$this->data['format_tags']   = 'p;h1;h2;h3;pre';

		$this->data['removeDialogTabs'] = 'image:advanced;link:advanced';

		$this->data['codemirror'] = array(
			'enableSearchTools'      => FALSE,
			'showSearchButton'       => FALSE,
			'showFormatButton'       => FALSE,
			'showCommentButton'      => FALSE,
			'showUncommentButton'    => FALSE,
			'showAutoCompleteButton' => FALSE
		);
	}


	public function setValue($section, $value) {
		$this->data[$section] = $value;
	}


	public function getConfiguration() {
		$return = '';

		foreach ($this->data as $key => $value) {
			$return .= "config.$key = " . json_encode($value) . ';';
		}

		return $return;
	}

} 