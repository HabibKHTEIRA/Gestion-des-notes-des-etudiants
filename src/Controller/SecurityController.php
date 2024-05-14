<?php

namespace App\Controller;

use Random\Engine\Secure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(Security $security): Response
    {
        return $this->render('security/index.html.twig');
    }
}
