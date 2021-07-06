<?php

namespace App\Http\Admin\Controller\Company;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Domain\User\Entity\User;
use App\Domain\Company\Entity\Company;
use App\Http\Admin\Form\EditCompanyType;
use App\Infrastructure\Company\CompanyService;

/**
 * Class CompanyAdminController
 * @package App\Http\Admin\Controller\Subscription
 * @Route("/admin/company")
 */
class CompanyAdminController extends AbstractController
{
    /**
     * @Route("/edit/{company}", name="admin_company_edit")
     */
    public function editCompany(Company $company = null, Request $request, CompanyService $companyService) : Response
    {
        if(!$company)
        {
            throw $this->createNotFoundException('Aucune entreprise trouvée.');
        }

        $formCompany = $this->createForm(EditCompanyType::class, $company);

        $formCompany->handleRequest($request);

        if ($formCompany->isSubmitted() && $formCompany->isValid()) {
            $company = $formCompany->getData();

            $responseCompany = $companyService->editCompany($company);

            if(!$responseCompany){
                $this->addFlash('success', 'Les informations de l\'entreprise ont bien été mis à jour.');
                return $this->redirectToRoute('admin_index');
            }else{
                $this->addFlash('danger', $responseCompany);
            }
        }
        return $this->render('admin/company/edit.html.twig', [
            'form_company' => $formCompany->createView(),
        ]);
    }
}