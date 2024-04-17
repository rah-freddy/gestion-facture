<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Form\InvoiceType;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    private $invoicereposotory;
    private $em;
    public function __construct(InvoiceRepository $invoiceRepository, EntityManagerInterface $em)
    {
        $this->invoicereposotory = $invoiceRepository;
        $this->em = $em;
    }
    #[Route('/invoice', name: 'list_invoice')]
    public function indexAction(): Response
    {
        $allInvoice = $this->invoicereposotory->findAll();

        return $this->render('invoice/index.html.twig', [
            'invoices' => $allInvoice,
        ]);
    }

    #[Route('/create-invoice', name: 'invoice_create')]
    public function createInvoiceAction(Request $request)
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $billDate = $form['billDate']->getData();
            $billNumber = $form['billNumber']->getData();
            $customer = $form['customer']->getData();

            $invoice->setBillDate($billDate);
            $invoice->setBillNumber($billNumber);
            $invoice->setCustomer($customer);

            $this->em->persist($invoice);
            $this->em->flush();
            $this->addFlash('success', 'Facture crée avec succès!');

            return $this->redirectToRoute('list_invoice');
        }

        return $this->render('invoice/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-invoice/{id}', name: 'invoice_delete')]
    public function deleteInvoiceAction(int $id)
    {
        $invoiceId = $this->invoicereposotory->find($id);
        $this->em->remove($invoiceId);
        $this->em->flush();

        return $this->redirectToRoute('list_invoice');
    }

    #[Route('/update-invoice/{id}', name: 'invoice_update')]
    public function updateInvoiceAction(Request $request, int $id)
    {
        $invoiceId = $this->invoicereposotory->find($id);
        $form = $this->createForm(InvoiceType::class, $invoiceId);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $billDate = $form['billDate']->getData();
            $billNumber = $form['billNumber']->getData();
            $customer = $form['customer']->getData();

            $invoiceId->setBillDate($billDate);
            $invoiceId->setBillNumber($billNumber);
            $invoiceId->setCustomer($customer);

            $this->em->persist($invoiceId);
            $this->em->flush();

            return $this->redirectToRoute('list_invoice');
        }

        return $this->render('invoice/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
