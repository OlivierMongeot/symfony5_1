<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\Collection;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends AbstractController
{


    /**
     * @Route("/{slug}", name="product_category", priority=-1)
     */
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(compact('slug'));

        if (!$category) {
            throw $this->createNotFoundException("La categorie existe pas");
        }
        return $this->render('product/category.html.twig', compact('category', 'slug'));
    }




    /**
     * @Route("/{category_slug}/{slug}", name="product_show" , priority=-1)
     * @param $slug
     * @param $id
     * @return Response
     **/
    public function show(string $slug, ProductRepository $productRepository): Response
    {
        $product = $productRepository->findOneBy(compact('slug'));
        // $url = $urlGeneratorInterface->generate('product_category', compact('slug'));
        // $url = $urlGeneratorInterface->generate('product_category', ['slug' => $product->getCategory()->getSlug()]);

        if (!$product) {
            throw $this->createNotFoundException("Le produit existe pas");
        }
        return $this->render('product/show.html.twig', compact('product'));
    }



    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     * @param $id
     * 
     **/
    public function edit( $id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em, ValidatorInterface $validator ): Response {


        $product = $productRepository->find($id);

        if($product === null){
            throw new NotFoundHttpException('Ce produit n\'existe pas pour édition');
        }

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'slug' => $product->getSlug(),
                'category_slug' => $product->getCategory()->getSlug()
            ]);
        }
        
        $formView = $form->createView();
        return $this->render('product/edit.html.twig', compact('product', 'formView'));
    }

    /**
     * @Route("/admin/product/create", name="product_create")
     **/
    public function create(
        FormFactoryInterface $formFactory,
        Request $request,
        SluggerInterface $sluggerInterface,
        EntityManagerInterface $em
    ): Response {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($sluggerInterface->slug($product->getName())));
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'slug' => $product->getSlug(),
                'category_slug' => $product->getCategory()->getSlug()
            ]);
        }
        $formView = $form->createView();
        return $this->render('product/create.html.twig', compact('formView'));
    }
}
