PHPX-Upload
===========

PHPX/Upload library

Usage Example
-------------

<pre>
&lt;?php
use \PHPX\Upload\UploaderFactory;

$destination = '/workspace/public/uploads';

$uploader = UploaderFactory::getInstance();
try {
    $result = $uploader->setDestination($destination)
                       ->setFileSize(array(
                            'options' => array('min' => '4KB', 'max' => '6MB'),
                            'messages' => array(
                                'fileSizeTooBig' => "文件大小'%size%'超过规定的最大值'%max%'",
                                'fileSizeTooSmall' => "文件大小'%size%'低于规定的最小值'%min%'",
                                'fileSizeNotFound' => '文件不可读或不存在'
                            )
                        ))
                       ->setExtension(array(
                            'options' => array('extension' => array('jpg,jpeg,png,gif,xls')),
                            'messages' => array(
                                'fileExtensionFalse' => '仅支持%extension%扩展类型',
                                'fileExtensionNotFound' => '文件不可读或不存在'
                            )
                        ))
                       ->setMimeType(array(
                            'options' => array('mimeType' => array('image/gif,image/jpg,image/jpeg,image/png')),
                            'messages' => array(
                                'fileMimeTypeFalse' => "文件mimetype:'%type%'暂不支持",
                                'fileMimeTypeNotDetected' => '无法检测到文件的mimetype',
                                'fileMimeTypeNotReadable' => '文件不可读或不存在'
                            )
                        ))
                       ->setRenameHandler(function($inputFileName, $fileInfo) {
                            $filename = $fileInfo['name'];
                            $pathInfo = pathinfo($filename);
                            $md5Filename = md5($filename.microtime(true));
                            return substr($md5Filename, 0, 3).DIRECTORY_SEPARATOR.
                                   substr($md5Filename, 3, 3).DIRECTORY_SEPARATOR.
                                   substr($md5Filename, 6, 3).DIRECTORY_SEPARATOR.
                                   substr($md5Filename, 9).(isset($pathInfo['extension']) ? '.'.$pathInfo['extension'] : '');
                       }, true)
                       ->upload();
    var_dump($result);
} catch(\Exception $e) {
    echo $e;
}
</pre>