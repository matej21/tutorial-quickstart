<?php declare(strict_types = 1);

namespace App\Components;


interface CommentFormControlFactory
{
	/**
	 * @return CommentFormControl
	 */
	public function create(callable $successCallback, $postId);
}
