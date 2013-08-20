<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <martin@vyvoj.net>
 * @created 19.8.13 14:46
 */

namespace snakeaas\NetteCKEditor;


use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

/**
 * Class NetteCKEditor
 * @package snakeaas\netteCKEditor
 */
class NetteCKEditor extends Control {

	protected $form;
	protected $wwwDir;


	public function __construct() {
		$this->form   = new Form();
		$this->wwwDir = null;
	}


	/**
	 *  Render a component
	 */
	public function render() {
		$this->template->setFile(__DIR__ . '/NetteCKEditor.latte');
		$this->template->render();
	}


	/**
	 * @return Form
	 */
	public function createComponentCkeditor() {

		$this->copyAssets();

		$this->form->addTextArea('editor', null, 80, 10)
			->setHtmlId('editor');

		$this->form->addSubmit('send', 'Save');

		$this->form->onSuccess[] = $this->process;

		return $this->form;
	}


	protected function copyAssets() {
		if ($this->wwwDir && !file_exists($this->wwwDir . '/ckeditor')) {
			self::copy(__DIR__ . '/../../ckeditor', $this->wwwDir . '/ckeditor');
		}
	}


	/**
	 * @param Form $form
	 */
	public function process(Form $form) {

	}


	public function getForm() {
		return $this->form;
	}


	/**
	 * @return string|NULL
	 */
	public function getWwwDir() {
		return $this->wwwDir;
	}


	/**
	 * @param string $wwwDir
	 */
	public function setWwwDir($wwwDir) {
		$this->wwwDir = $wwwDir;
	}



	static function copy($source, $dest, $overwrite = true) {
		$dir = opendir($source);
		@mkdir($dest);
		while(false !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if(is_dir($source . '/' . $file)) {
					self::copy($source . '/' . $file, $dest . '/' . $file);

				} else {
					if($overwrite || !file_exists($dest . '/' . $file)) {
						copy($source . '/' . $file, $dest . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}
}
