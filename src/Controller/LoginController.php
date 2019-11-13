<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginController extends AbstractController
{

    private $githubClient;

    public function _construct($githubClient){
            $this->githubClient = $githubClient;
    }


    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    /**
     * @Route("/login/github", name="github")
     */
    public function github(UrlGeneratorInterface $generator)
    {
       $url =  $generator->generate('dashboard',[],UrlGeneratorInterface::ABSOLUTE_URL);
       
       return New RedirectResponse("https://github.com/login/oauth/authorize?client_id=$this->githubClient&redirect_uri=".$url);
    }

     /**
     * @Route("/dashboard", name="github")
     */
    public function dashboard()
    {
       return New Response('<body><h2></h2></body>');
    }
}
