<?php
/**
 * @author  Martin "Snake.AAS" Grossmann <martin@vyvoj.net>
 * @created 19.8.13 14:46
 */

namespace snakeaas\NetteCKEditor;


use Nette\Application\Responses\JsonResponse;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\DirectoryNotFoundException;
use Nette\Image;
use Nette\Utils\Finder;
use snakeaas\NetteCKEditor\Html\Html;

/**
 * Class NetteCKEditor
 * @package snakeaas\netteCKEditor
 */
class NetteCKEditor extends Control {

	const PUBLIC_DIR_NAME = 'public';
	const TEMP_DIR_NAME   = 'temp';

	protected $form;
	protected $wwwDir;
	protected $uploadDirName;
	protected $uploadDir;
	protected $publicDir;
	protected $tempDir;
	protected $config;

	public $onSuccess;


	public function __construct($wwwDir, $uploadDirName = 'upload') {
		$this->form   = new Form();
		$this->config = new Config();

		$this->wwwDir = $wwwDir;

		$this->uploadDirName = $uploadDirName;

		$this->uploadDir = $this->wwwDir . DIRECTORY_SEPARATOR . $uploadDirName;
		$this->publicDir = $this->uploadDir . DIRECTORY_SEPARATOR . self::PUBLIC_DIR_NAME;
		$this->tempDir   = $this->uploadDir . DIRECTORY_SEPARATOR . self::TEMP_DIR_NAME;

		$this->prepareEnvironment();
	}


	/**
	 *  Render a component
	 */
	public function render() {

		$this->template->setFile(__DIR__ . '/NetteCKEditor.latte');
		$this->template->config = $this->config->getConfiguration();

		$this->template->render();
	}


	/**
	 * Create form containing textarea to be replaced with CKEditor
	 *
	 * @return Form
	 */
	public function createComponentCkeditor() {

		$this->form->addTextArea('editor', null, 80, 10)
			->setHtmlId('editor');

		$this->form->addSubmit('send', 'Save');

		$this->form->onSuccess[] = $this->save;

		return $this->form;
	}


	/**
	 * Pre-processing HTML obtained from CKEditor
	 *
	 * @param Form $form
	 */
	public function save(Form $form) {
		$values = $form->getValues();

		$html = new Html();
		$html->setHtml($values['editor']);

		foreach ($html->getImages() as $image) {
			list($width, $height) = $image->getDimensionsFromStyle();

			$newFilename = substr($image->getSrc()->getFilename(), 0, strrpos($image->getSrc()->getFilename(), '.')) .
				"[{$width}x{$height}]" .
				substr($image->getSrc()->getFilename(), strrpos($image->getSrc()->getFilename(), '.'));

			$newSrc = $image->getSrc()->getPathinfo()->getPath() . '/' . self::TEMP_DIR_NAME . '/' . $newFilename;

			$imFile = realpath($this->publicDir . DIRECTORY_SEPARATOR . $image->getSrc()->getFilename());

			if ($imFile !== FALSE) {
				$im = Image::fromFile($imFile);
				// TODO: resize only if image is larger than real dimensions
				$im->resize($width, $height);
				if ($im->save($this->tempDir . DIRECTORY_SEPARATOR . $newFilename)) {
					$image->setSrc($newSrc);
				}

			}
		}

		$values['editor'] = $html->getHtml();
		$form->setValues($values, TRUE);

		$this->onSuccess($form);
	}


	/**
	 * Function sending JsonResponse with available images on filesystem
	 */
	public function handleGetJsonImageList() {
		$return = array();

		$finder = Finder::findFiles('*.jpg', '*.png', '*.gif')
			->from($this->publicDir);

		foreach ($finder as $file) {
			$tmp          = array();
			$tmp['image'] = $this->template->basePath . '/' . $this->uploadDirName . '/' . self::PUBLIC_DIR_NAME . '/' . $file->getFilename();

			if (file_exists($this->uploadDir . DIRECTORY_SEPARATOR . 'mini' . DIRECTORY_SEPARATOR . $file->getFilename())) {
				$tmp['thumb'] = $this->template->basePath . '/' . $this->uploadDirName . '/mini/' . $file->getFilename();
			}

			$return[] = $tmp;
		}
		$this->presenter->sendResponse(new JsonResponse($return));
	}


	/**
	 *    Copy necessary files, create directories for proper work
	 */
	protected function prepareEnvironment() {
		$this->copyAssets();

		self::checkDirectory($this->uploadDir);
		self::checkDirectory($this->publicDir);
		self::checkDirectory($this->tempDir);
	}


	/**
	 * Check if directory exists, if not, then try to create it.
	 * Throws an exception if directory can be created
	 *
	 * @param string $directory
	 *
	 * @throws \Nette\DirectoryNotFoundException
	 */
	static function checkDirectory($directory) {

		if (file_exists($directory)) {
			if (!is_dir($directory) || !is_writable($directory)) {
				throw new DirectoryNotFoundException("File {$directory} is not writable directory");
			}
		} else {
			// Intentionally because of permission denied warning
			if (@mkdir($directory) === FALSE) {
				throw new DirectoryNotFoundException("Directory {$directory} does not exist and can not be created");
			}
		}
	}


	/**
	 * Copy CKEditor files if there are none
	 */
	protected function copyAssets() {
		$targetDir = $this->wwwDir . '/ckeditor';

		if (!file_exists($targetDir) && is_writable($this->wwwDir)) {
			self::copy(__DIR__ . '/../../ckeditor', $targetDir);
		}
	}


	/**
	 * Recursive folder copying
	 */
	static function copy($source, $dest, $overwrite = TRUE) {
		$dir = opendir($source);
		@mkdir($dest);
		while (FALSE !== ($file = readdir($dir))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($source . '/' . $file)) {
					self::copy($source . '/' . $file, $dest . '/' . $file);

				} else {
					if ($overwrite || !file_exists($dest . '/' . $file)) {
						copy($source . '/' . $file, $dest . '/' . $file);
					}
				}
			}
		}
		closedir($dir);
	}


	/**
	 * @return Form
	 */
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


	/**
	 * @return Config
	 */
	public function getConfig() {
		return $this->config;
	}


	/**
	 * @param string $uploadDir
	 */
	public function setUploadDir($uploadDir) {
		$this->uploadDir = $uploadDir;
	}


	/**
	 * @return string
	 */
	public function getUploadDir() {
		return $this->uploadDir;
	}


}
