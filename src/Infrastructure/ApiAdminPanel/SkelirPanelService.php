<?php

namespace App\Infrastructure\ApiAdminPanel;

use App\Domain\ApiAdminConnection\Entity\ApiAdminConnection;
use App\Domain\Company\Entity\Company;
use App\Domain\Subscription\Entity\Subscription;
use App\Domain\User\Entity\User;
use App\Infrastructure\Company\CompanyService;
use App\Infrastructure\Subscription\SubscriptionService;
use App\Infrastructure\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;

class SkelirPanelService
{
    private $entityManager;

    private $userService;

    private $subscriptionService;

    private $companyService;

    public function __construct(EntityManagerInterface $entityManager,
                                UserService $userService,
                                SubscriptionService $subscriptionService,
                                CompanyService $companyService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->subscriptionService = $subscriptionService;
        $this->companyService = $companyService;
    }

    private function findUser($email) : ?user
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    private function findCompany($user_panel_id) : ?Company
    {
        return $this->entityManager
            ->getRepository(Company::class)
            ->findOneBy(['user_panel_id' => $user_panel_id]);
    }

    private function findSubscription($subscription_panel_id) : ?Subscription
    {
        return $this->entityManager
            ->getRepository(Subscription::class)
            ->findOneBy(['subscription_panel_id' => $subscription_panel_id]);
    }

    private function createCompany($request, $user)
    {
        $end_at = \DateTime::createFromFormat('d-m-Y H:i:s', $request->request->get('end_at'));
        $subscription = $this->subscriptionService->createSubscriptionFromSkelirPanel(
            $request->request->get('subscription_panel_id'),
            $end_at
        );

        $company = $this->companyService->createCompanyFromSkelirPanel(
            $request->request->get('user_panel_id'),
            $user,
            $subscription
        );

        $em = $this->entityManager;
        $em->persist($subscription);
        $em->persist($company);
        $em->flush();
    }

    public function createAccount($request) : ? String
    {
        $company = $this->findCompany($request->request->get('user_panel_id'));
        $subscription = $this->findSubscription($request->request->get('subscription_panel_id'));

        /**
         * Situation : Aucune company, subscription trouvé
         */
        if(!$company && !$subscription)
        {
            $user = $this->findUser($request->request->get('email'));

            if(!$user)
            {
                $user = $this->userService->createUserFromSkelirPanel($request->request->get('email'));
                $this->createCompany($request, $user);
            } else {
                if(!$user->getCompany()){
                    $this->createCompany($request, $user);
                }else {
                    return "L'email utilisé appartient déjà a un utilisateur dans une autre entreprise";
                }
            }

            return null;
        }else{
            return "Une erreur est survenue. Le compte semble déjà créer pour cet abonnement.";
        }
    }

    public function updateAccount($request) : ?String
    {
        $user = $this->findUser($request->request->get('email'));
        $end_at = \DateTime::createFromFormat('d-m-Y H:i:s', $request->request->get('end_at'));

        if(!$user)
        {
            $user = $this->userService->createUserFromSkelirPanel($request->request->get('email'));
        }

        $company = $this->findCompany($request->request->get('user_panel_id'));
        $subscription = $this->findSubscription($request->request->get('subscription_panel_id'));

        if(!$company && !$subscription)
        {
            return "Une erreur est survenue. Aucun compte pour cet Abonnement.";
        }else {
            if($company->getSubscription() == $subscription)
            {
                $subscription->setEndAt($end_at);
            }
        }

    }
}