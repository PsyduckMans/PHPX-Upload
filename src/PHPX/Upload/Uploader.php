<?php
/**
 * PHP Extension Library (https://github.com/PsyduckMans/PHPX-Upload)
 *
 * @link      https://github.com/PsyduckMans/PHPX-Upload for the canonical source repository
 * @copyright Copyright (c) 2014 PsyduckMans (https://ninth.not-bad.org)
 * @license   https://github.com/PsyduckMans/PHPX-Upload/blob/master/LICENSE MIT
 * @author    Psyduck.Mans
 */

namespace PHPX\Upload;

/**
 * Class Uploader
 * @package PHPX\Upload
 */
abstract class Uploader {

	private $destination;

	private $filesize;

	private $extension;

	private $mimeType;

	abstract public function upload();

	/**
	 * upload file rename handler
	 *
	 * @param callable $handler
	 * @param bool $override
	 * @return string
	 */
	abstract public function setRenameHandler(callable $handler, $override=true);

	/**
	 * @param mixed $destination
	 * @return $this
	 */
	public function setDestination($destination)
	{
		$this->destination = rtrim($destination, '/\\');
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDestination()
	{
		return $this->destination;
	}

	/**
	 * @param mixed $extension
	 * @return $this
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	 * @param mixed $filesize
	 * @return $this
	 */
	public function setFilesize($filesize)
	{
		$this->filesize = $filesize;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFilesize()
	{
		return $this->filesize;
	}

	/**
	 * @param mixed $mime
	 * @return $this
	 */
	public function setMimeType($mime)
	{
		$this->mimeType = $mime;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}

} 