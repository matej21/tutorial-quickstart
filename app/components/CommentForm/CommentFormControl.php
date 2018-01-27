<?php declare(strict_types = 1);

namespace App\Components;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use Nette\Mail\IMailer;


class CommentFormControl extends Control
{
	/** @var Context */
	private $database;

	/** @var int */
	private $postId;

	/** @var callable */
	private $successCallback;

	/** @var IMailer */
	private $mailer;


	public function __construct(callable $successCallback, $postId, Context $database, IMailer $mailer)
	{
		$this->database = $database;
		$this->postId = $postId;
		$this->successCallback = $successCallback;
		$this->mailer = $mailer;
	}


	protected function createComponentForm()
	{
		$form = new Form();
		$form->addText('name', 'Your name:')
			->setRequired();

		$form->addEmail('email', 'Email:');

		$form->addTextArea('content', 'Comment:')
			->setRequired();

		$form->addSubmit('send', 'Publish comment');
		$form->onSuccess[] = [$this, 'processForm'];

		return $form;
	}


	public function processForm($form, $values)
	{
		$comment = $this->database->table('comments')->insert([
			'post_id' => $this->postId,
			'name' => $values->name,
			'email' => $values->email,
			'content' => $values->content,
		]);
		// $this->mailer->send(...);
		($this->successCallback)($this, $comment);
	}


	public function render()
	{
		$this['form']->render();
	}
}
