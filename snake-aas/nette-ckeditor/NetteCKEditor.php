<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <martin@vyvoj.net>
 * @created 19.8.13 14:46
 */

namespace snakeaas\netteCKEditor;


use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


/**
 * Class NetteCKEditor
 * @package snakeaas\netteCKEditor
 */
class NetteCKEditor extends Control {

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
	public function createComponentCKEditor() {
		$form = new Form();

		$form->addTextArea('editor', null, 80, 10)
			->setHtmlId('editor');

		$form->addSubmit('send', 'Save');

		$form->onSuccess[] = $this->process;

		return $form;
	}


	/**
	 * @param Form $form
	 */
	public function process(Form $form) {

	}

} 