<?php
namespace App\Controller;

use App\Model\UserModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class DefaultController extends AbstractController
{
    public function __construct(
        protected Environment $twig,
        private UserModel $userModel,
    ) {
    }

    public function index(Request $request): Response
    {
        return $this->render('index', ['users' => $this->userModel->getAll()]);
    }

    public function about()
    {
        echo '2';
    }
}
