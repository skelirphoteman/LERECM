<?php

namespace App\Http\Admin\Controller\Subscription;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\User\Entity\User;
use App\Domain\Subscription\Entity\Subscription;
use App\Http\Admin\Form\AddSubscriptionType;
use App\Infrastructure\Subscription\SubscriptionService;


/**
 * Class SubscriptionAdminController
 * @package App\Http\Admin\Controller\Subscription
 * @Route("/admin/subscription")
 */
class SubscriptionAdminController extends AbstractController
{
    /**
     * @Route("/add/{user}", name="admin_subscription_add")
     */
    public function addSubscription(User $user = null, Request $request, SubscriptionService $subscriptionService) : Response
    {
        if(!$user)
        {
            throw $this->createNotFoundException('Aucun utilisateur trouvé');
        }

        if($user->getCompany())
        {
            $this->addFlash("danger", "Cet utilisateur possède déjà un abonnement");
            return $this->redirectToRoute("admin_index");
        }

        $subscription = new Subscription();

        $formSubscritpion = $this->createForm(AddSubscriptionType::class, $subscription);

        $formSubscritpion->handleRequest($request);

        if ($formSubscritpion->isSubmitted() && $formSubscritpion->isValid()) {
            $subscription = $formSubscritpion->getData();

            $responseSubscription = $subscriptionService->createSubscription($subscription, $user);

            if(!$responseSubscription){
                $this->addFlash('success', 'L\'abonnement a bien été créer.');
                return $this->redirectToRoute('admin_index');
            }else{
                $this->addFlash('danger', $responseSubscription);
            }
        }
        return $this->render('admin/subscription/add.html.twig', [
            'form_subscription' => $formSubscritpion->createView(),
        ]);
    }

    /**
     * @Route("/edit/{subscription}", name="admin_subscription_edit")
     */
    public function editSubscription(Subscription $subscription = null, Request $request, SubscriptionService $subscriptionService) : Response
    {
        if(!$subscription)
        {
            throw $this->createNotFoundException('Aucun abonnement trouvé');
        }

        $formSubscritpion = $this->createForm(AddSubscriptionType::class, $subscription);

        $formSubscritpion->handleRequest($request);

        if ($formSubscritpion->isSubmitted() && $formSubscritpion->isValid()) {
            $subscription = $formSubscritpion->getData();

            $responseSubscription = $subscriptionService->editSubscription($subscription);

            if(!$responseSubscription){
                $this->addFlash('success', 'L\'abonnement a bien été mis-à-jour-+.');
                return $this->redirectToRoute('admin_index');
            }else{
                $this->addFlash('danger', $responseSubscription);
            }
        }
        return $this->render('admin/subscription/edit.html.twig', [
            'form_subscription' => $formSubscritpion->createView(),
        ]);
    }
}