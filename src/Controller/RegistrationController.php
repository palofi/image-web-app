<?php

namespace App\Controller;

use App\Services\Email\EmailService;
use App\Services\Register\RegisterService;
use App\Services\Security\Credentials;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{


    /**
     * @var EmailService
     */
    private $emailService;

    private $registrationService;

    /**
     * RegistrationController constructor.
     * @param EmailService $emailService
     */
    public function __construct(EmailService $emailService,RegisterService $registrationService)
    {
        $this->emailService = $emailService;
        $this->registrationService = $registrationService;

    }


    /**
     * @Route("/register", name="register_index")
     */
    public function registerIndex(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        $credentials = (new Credentials('',''))->createFromRequest($request);

        if ($credentials){
            $this->registrationService->register($credentials);
        }

        return $this->render('registration/index.html.twig');
    }



}
