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


	public function __construct() {
		$this->form = new Form();
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
		$this->form->addTextArea('editor', null, 80, 10)
			->setHtmlId('editor');

		$this->form->addSubmit('send', 'Save');

		$this->form->onSuccess[] = $this->process;

		return $this->form;
	}


	/**
	 * @param Form $form
	 */
	public function process(Form $form) {

	}

	public function getForm() {
		return $this->form;
	}

} 