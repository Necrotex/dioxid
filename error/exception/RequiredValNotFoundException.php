<?php
/**
 * file_name  - project_name
 * @author Andre 'Necrotex' Peiffer <necrotex@gmail.com>
 * @version 1.0
 * @package package_name
 */

namespace dioxid\error\exception;

use dioxid\error\exception\BaseException;

class RequiredValNotFoundException extends BaseException {
	protected $code = 500;
}

?>