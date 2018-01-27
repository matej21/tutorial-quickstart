<?php declare(strict_types = 1);

namespace App\Presenters;

use App\Components\CommentFormControl;
use App\Components\CommentFormControlFactory;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;


class PostShowPresenter extends BasePresenter
{
	/** @var Nette\Database\Context @inject */
	public $database;

	/** @var CommentFormControlFactory @inject */
	public $commentFormControlFactory;


	public function renderDefault($postId)
	{
		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->error('Post not found');
		}

		$this->template->post = $post;
		$this->template->comments = $post->related('comment')->order('created_at');
	}


	protected function createComponentCommentForm()
	{
		$successCallback = function (CommentFormControl $control, ActiveRow $comment) {
			$this->flashMessage('Thank you for your comment id #' . $comment->id, 'success');
			$this->redirect('this');
		};
		$postId = $this->getParameter('postId');
		return $this->commentFormControlFactory->create($successCallback, $postId);
	}
}
