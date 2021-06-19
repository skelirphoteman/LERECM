<?php


namespace App\Infrastructure\User;

use App\Domain\UserClient\Entity\UserClient;
use App\Domain\User\Entity\User;
use App\Domain\Client\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class UserClientService
{
    private $entityManager;

    private $passwordEncoder;

    private $twig;


    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder,
                                Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->twig = $twig;
    }

    private function generatePassword($number = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $number; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private function insertUserClient(Client $client): UserClient
    {
        $em = $this->entityManager;
        $userClient = new UserClient();
        $userClient->setUuid($client->getId());
        $password = $this->generatePassword();
        $userClient->setPassword($this->passwordEncoder->encodePassword(
            $userClient,
            $password
        ));
        $userClient->addRole("ROLE_CLIENT");
        $em->persist($userClient);
        $em->flush();

        $this->generatePDF($client, $userClient, $password);

        return $userClient;
    }

    private function updateUserClient(UserClient $userClient, Client $client): UserClient
    {
        $em = $this->entityManager;
        $password = $this->generatePassword();
        $userClient->setPassword($this->passwordEncoder->encodePassword(
            $userClient,
            $password
        ));
        $em->persist($userClient);
        $em->flush();

        $this->generatePDF($client, $userClient, $password);

        return $userClient;
    }

    private function generatePDF(Client $client, UserClient $userClient, $password)
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->twig->render('pdf/client/connexion.html.twig', [
            'userClient' => $userClient,
            'company' => $client->getCompany(),
            'client' => $client,
            'password' => $password,
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $dompdf->stream($client->getId() . "-" . $client->getSurname() . "-" . $client->getName() . "-" . $client->getCompanyName() . ".pdf", [
            "Attachment" => true
        ]);

    }

    private function accessIsValid(User $user) : bool
    {
        if(!$user->getCompany()->getSubscription()->subIsValid()) return false;

        return true;
    }

    public function createClientAccount(User $user, Client $client) : ?String
    {

        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de client. Veuillez vérifier que votre abonnement est toujours valide.";
        }
        $this->insertUserClient($client);

        return null;
    }

    public function updateClientAccount(User $user, Client $client, UserClient $userClient) : ?String
    {

        if(!$this->accessIsValid($user)){
            return "Vous ne pouvez pas ajouter de client. Veuillez vérifier que votre abonnement est toujours valide.";
        }
        $this->updateUserClient($userClient, $client);

        return null;
    }

}