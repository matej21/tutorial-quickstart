<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class PostPresenter extends Nette\Application\UI\Presenter
{
	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	public function actionCreate()
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
	}


	public function actionEdit($postId)
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}

		$post = $this->database->table('posts')->get($postId);
		if (!$post) {
			$this->error('Post not found');
		}
		$this['postForm']->setDefaults($post->toArray());
	}


	protected function createComponentPostForm()
	{
		if (!$this->getUser()->isLoggedIn()) {
			$this->error('You need to log in to create or edit posts');
		}

		$form = new Form;
		$form->addText('title', 'Title:')
			->setRequired();
		$form->addTextArea('content', 'Content:')
			->setRequired();

		$form->addSubmit('send', 'Save and publish');
		$form->onSuccess[] = [$this, 'postFormSucceeded'];

		return $form;
	}


	public function postFormSucceeded($form, $values)
	{
		$postId = $this->getParameter('postId');

		if ($postId) {
			$post = $this->database->table('posts')->get($postId);
			$post->update($values);
		} else {
			$post = $this->database->table('posts')->insert($values);
		}

		$this->flashMessage('Post was published', 'success');
		$this->redirect('show', $post->id);
	}

}
