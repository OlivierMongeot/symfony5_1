<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;



class CategoryController extends AbstractController
{

    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

   
    /**
    * @Route("/admin/category/create", name="category_create")
    *  
    **/
    public function create(Request $request, SluggerInterface $slugger, 
    EntityManagerInterface $em)     
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homePage');
        }

        return $this->render('category/create.html.twig', [
            'formView' => $form->createView(),
        ]);
    }


    /**
    *   @Route("/admin/category/{id}/edit", name="category_edit")
    *   @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès à cette page")  
    */
    public function edit(int $id, CategoryRepository $categoryRepo, Request $request, SluggerInterface $slugger, EntityManagerInterface $em){

        $category = $categoryRepo->find($id); 

        if($category === null){
            throw new NotFoundHttpException('Cette catégorie n\'existe pas');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homePage');
        }

        $formView = $form->createView();
        return $this->render('category/edit.html.twig', compact('category', 'formView'));
    }

}
