<?php

namespace App\Http\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Domain\UserClient\Entity\UserClient;

class ClientSecurityController extends AbstractController
{
    /**
     * @Route("/client/login", name="client_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_client.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/client/index", name="client_index")
     */
    public function index() : Response
    {
        return new Response($this->getUser()->getUsername());
    }

    /**
     * @Route("/client/create", name="client_create")
     */
    public function create(UserPasswordEncoderInterface $passwordEncoder)
    {
        $client = new UserClient();

        $client->setUuid("1234");
        $client->addRole('ROLE_CLIENT');

        $client->setPassword($passwordEncoder->encodePassword(
            $client,
            "1234"
        ));
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();
    }
}
