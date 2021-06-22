<?php

namespace App\Http\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Infrastructure\User\ResetPasswordService;
use App\Domain\User\Entity\ResetPassword;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
             return $this->redirectToRoute('app_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/reset/password", name="app_reset_password")
     */
    public function resetPassword(Request $request, ResetPasswordService $resetPasswordService) : Response
    {
        $email = $request->request->get('email');
        if($email){
            $token = $resetPasswordService->createToken($email);

            if($token){
                $this->addFlash('danger', $token);
            }else{
                $this->addFlash('success', "Un email viens de vous être envoyé afin de modifier votre mot de passe.");
            }
        }

        return $this->render('security/reset_password.html.twig');
    }

    /**
     * @Route("/reset/password/{token}", name="app_reset_password_new")
     */
    public function resetPasswordToken(String $token = null, Request $request, ResetPasswordService $resetPasswordService) : Response
    {
        $resetPassword = $this->getDoctrine()->getRepository(ResetPassword::class)->findOneBy(['token' => $token]);

        if(!$resetPassword){
            throw $this->createNotFoundException('Les informations renseignées ne sont pas correctes');
        }

        if($request->request->get('password') && $request->request->get('password_second')){
            if($request->request->get('password') == $request->request->get('password_second')){
                $responseResetPassword = $resetPasswordService->resetPassword($resetPassword ,$request->request->get('password'));
                if($responseResetPassword){
                    $this->addFlash('danger', $responseResetPassword);
                }else{
                    $this->addFlash('success', "Mot de passe bien changé.");
                    return $this->redirectToRoute('app_login');
                }
            }else{
                $this->addFlash('danger', 'Les deux mots de passes ne correspondent pas.');
            }
        }

        return $this->render('security/reset_password_new.html.twig');
    }
}
