<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    private $em;
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
    }
    #[Route('/product', name: 'list_product')]
    public function indexAction(): Response
    {
        $allProducts = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $allProducts,
        ]);
    }

    #[Route('/create-product', name: 'product_create')]
    public function createProductAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $description = $form['description']->getData();
            $amount = $form['amount']->getData();
            $quantity = $form['quantity']->getData();
            $amountTVA = $form['amountTVA']->getData();
            if ($amountTVA === null) {
                $amountTVA = (float)$amount * $quantity * 0.2; // 20% de TVA
                $product->setAmountTVA($amountTVA);
            } else {
                $product->setAmountTVA($amountTVA);
            }
            $totalTVA = (float)($amount * $quantity) + (float)$product->getAmountTVA();
            $product->setTotalTVA($totalTVA);
            $invoice = $form['invoice']->getData();

            $product->setDescription($description);
            $product->setAmount($amount);
            $product->setInvoice($invoice);
            $product->setQuantity($quantity);

            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Produit crée avec succès!');

            return $this->redirectToRoute('list_product');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-product/{id}', name: 'product_delete')]
    public function deleteProductAction(int $id)
    {
        $productId = $this->productRepository->find($id);
        $this->em->remove($productId);
        $this->em->flush();
        $this->addFlash('success', 'Produit supprimé avec succès!');

        return $this->redirectToRoute('list_product');
    }

    #[Route('/update-product/{id}', name: 'product_update')]
    public function updateProductAction(Request $request, int $id)
    {
        $productId = $this->productRepository->find($id);
        $form = $this->createForm(ProductType::class, $productId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $description = $form['description']->getData();
            $amount = $form['amount']->getData();
            $quantity = $form['quantity']->getData();
            $amountTVA = $form['amountTVA']->getData();
            $amountTVA = (float)$amount * $quantity * 0.2;
            $productId->setAmountTVA($amountTVA);
            $totalTVA = (float)($amount * $quantity) + (float)$productId->getAmountTVA();
            $productId->setTotalTVA($totalTVA);
            $invoice = $form['invoice']->getData();

            $productId->setDescription($description);
            $productId->setAmount($amount);
            $productId->setInvoice($invoice);
            $productId->setQuantity($quantity);

            $this->em->persist($productId);
            $this->em->flush();

            return $this->redirectToRoute('list_product');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
