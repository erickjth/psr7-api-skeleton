<?php
namespace App\Controller;

use App\Controller\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

class AccountController extends AbstractController
{
	function listAction(ServerRequestInterface $request)
	{
		return $this->toJson([
			'account-1' => [],
			'account-2' => [],
			'account-3' => [],
			'account-4' => [],
			'account-5' => [],
		]);
	}

	function getAction(ServerRequestInterface $request)
	{
		$attributes = $request->getAttributes();

		return $this->toJson([
			'id' => $attributes['id']
		]);
	}
}