<?php declare(strict_types = 1);

namespace App\Presenters;

use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{
	public function formatTemplateFiles()
	{
		list(, $presenter) = \Nette\Application\Helpers::splitName($this->getName());
		$rc = new \ReflectionClass($this);
		return [dirname($rc->getFileName()) . '/' . $presenter . '.latte'];
	}
}
