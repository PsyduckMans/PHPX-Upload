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
use PHPX\Upload\Uploader\ZF2Uploader;

/**
 * Class UploaderFactory
 * @package PHPX\Upload
 */
class UploaderFactory {

	/**
	 * mode list
	 */
	const MODE_ZENDFRAMEWORK2 = 0x00000001;

	/**
	 * default mode
	 */
	const MODE_DEFAULT = self::MODE_ZENDFRAMEWORK2;

	/**
	 * @param $mode
	 * @return Uploader
	 */
	public static function getInstance($mode=self::MODE_DEFAULT) {
		switch($mode) {
			case self::MODE_ZENDFRAMEWORK2:
			default:
				return new ZF2Uploader();
		}
	}

} 