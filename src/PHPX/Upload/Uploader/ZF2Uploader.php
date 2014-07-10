<?php
/**
 * PHP Extension Library (https://github.com/PsyduckMans/PHPX-Upload)
 *
 * @link      https://github.com/PsyduckMans/PHPX-Upload for the canonical source repository
 * @copyright Copyright (c) 2014 PsyduckMans (https://ninth.not-bad.org)
 * @license   https://github.com/PsyduckMans/PHPX-Upload/blob/master/LICENSE MIT
 * @author    Psyduck.Mans
 */

namespace PHPX\Upload\Uploader;

use PHPX\Upload\Uploader;
use \Zend\Validator\File;

/**
 * Class ZF2Uploader
 * @package PHPX\Upload\Uploader
 */
class ZF2Uploader extends Uploader {

	/**
	 * @var \Zend\File\Transfer\Adapter\Http
	 */
	private $adapter;

	/**
	 * __construct
	 */
	public function __construct() {
		$this->adapter = new \Zend\File\Transfer\Adapter\Http();
	}

	/**
	 * upload
	 */
	public function upload() {
		$this->loadValidators();
		return $this->doUpload();
	}

	/**
	 * load validators to adapter
	 */
	private function loadValidators() {
		$validators = array();
		if($this->getFilesize()) {
			$config = $this->getFilesize();
			$options = isset($config['options']) ? $config['options'] : null;
			$messages = (isset($config['messages']) && is_array($config['messages'])) ? $config['messages'] : array();
			$validator = new File\Size($options);
			$validator->setMessages($messages);
			array_push($validators, $validator);
		}
		if($this->getExtension()) {
			$config = $this->getExtension();
			$options = isset($config['options']) ? $config['options'] : null;
			$messages = (isset($config['messages']) && is_array($config['messages'])) ? $config['messages'] : array();
			$validator = new File\Extension($options);
			$validator->setMessages($messages);
			array_push($validators, $validator);
		}
		if($this->getMimeType() && $this->isFileInfoExtensionLoaded()) {
			$config = $this->getMimeType();
			$options = isset($config['options']) ? $config['options'] : null;
			$messages = (isset($config['messages']) && is_array($config['messages'])) ? $config['messages'] : array();
			$validator = new File\MimeType($options);
			$validator->setMessages($messages);
			array_push($validators, $validator);
		}
		$this->adapter->setValidators($validators);
	}

	/**
	 * do upload
	 *
	 * @return array
	 */
	private function doUpload() {
		$result = array();
		foreach ($this->adapter->getFileInfo() as $inputFileName => $fileInfo) {
			$errFlag = false;
			// init result
			$result[$inputFileName] = array('code' => 0);
			// set destination
			$this->adapter->setDestination($this->getDestination());
			// check valid
			if(!$this->adapter->isValid($inputFileName)) {
				if(current($this->adapter->getErrors()) == \Zend\Validator\File\Upload::NO_FILE) {
					$result[$inputFileName] = null;
					continue;
				}
				$errFlag = true;
				$result[$inputFileName]['code'] = -1;
				$result[$inputFileName]['error'] = current($this->adapter->getMessages());
			}
			// load rename handler
			if(!$errFlag && $this->renameHandler) {
				$renamePath = call_user_func_array($this->renameHandler, array($inputFileName, $fileInfo));
				$target = $this->generateTargetPath($renamePath);
				$targetPathInfo = pathinfo($target);
				$targetDirname = $targetPathInfo['dirname'];
				if(!is_dir($targetDirname)) {
					mkdir($targetDirname, 0744, true);
				}
				$this->adapter->addFilter('File\Rename', array(
					'target' => $target,
					'overwrite' => $this->override
				));
			}
			// receive
			if(!$errFlag) {
				if(!$this->adapter->receive($inputFileName)) {
					$errFlag = true;
					$result[$inputFileName]['code'] = -1;
					$result[$inputFileName]['error'] = current($this->adapter->getMessages());
				} else {
					$result[$inputFileName]['path'] = $this->adapter->getFileName($inputFileName);
				}
			}
		}
		return $result;
	}

	/**
	 * generate target path
	 *
	 * @param string $renamePath
	 * @return string
	 */
	private function generateTargetPath($renamePath) {
		$filePath = $this->getDestination().DIRECTORY_SEPARATOR.$renamePath;
		return $filePath;
	}

	/**
	 * @var callable
	 */
	private $renameHandler;
	/**
	 * @var bool
	 */
	private $override;
	/**
	 * upload file rename handler
	 *
	 * @param callable $handler
	 * @param bool $override
	 * @return $this
	 */
	public function setRenameHandler(callable $handler, $override=true)
	{
		$this->renameHandler = $handler;
		$this->override = !!$override;
		return $this;
	}

	/**
	 * check fileinfo php extension is loaded
	 *
	 * @return bool
	 */
	private function isFileInfoExtensionLoaded() {
		return class_exists('finfo', false);
	}

}