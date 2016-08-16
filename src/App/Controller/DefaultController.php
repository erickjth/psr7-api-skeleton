<?php
namespace App\Controller;

use App\Controller\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

class DefaultController extends AbstractController
{
	function homeAction(ServerRequestInterface $request)
	{
		return $this->toJson([
			'time' => time(),
		]);
	}
	// .....
}