<?php declare(strict_types=1);

namespace App\Controller;


use App\Services\Security\Credentials;

use App\Services\Security\Security;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct( Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/login", name="login_index", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function loginIndex(Request $request): Response
    {
        return $this->render('login/index.html.twig', [
        ]);
    }


    /**
     * @Route("/login", name="app_login", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): Response
    {

        try {
            $loginData = [
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ];
            if (!isset($loginData['email'], $loginData['password'])) {
                return $this->render('login/index.html.twig', ['error' => "Missing 'email' or 'password' field in request"]);
            }

            $credentials = new Credentials($loginData['email'], $loginData['password']);
        } catch (Exception $e) {
            return $this->render( 'login/index.html.twig',  ['error' => "Received data is not valid."]);
        }

        try {
            return $this->render('login/index.html.twig',$this->security->login($credentials));
        } catch (Exception $e) {
            return $this->render('login/index.html.twig', ['error' => 'Bad credentials']);

        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


}
