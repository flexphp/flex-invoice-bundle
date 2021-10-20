<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Controller;

use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\BillTypeFormType;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\CreateBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\DeleteBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\IndexBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\ReadBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\Request\UpdateBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\CreateBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\DeleteBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\IndexBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\ReadBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillType\UseCase\UpdateBillTypeUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BillTypeController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="bill-types.index")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexBillTypeUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest()
            ? '@FlexPHPInvoice/billType/_ajax.html.twig'
            : '@FlexPHPInvoice/billType/index.html.twig';

        $request = new IndexBillTypeRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'billTypes' => $response->billTypes,
        ]);
    }

    /**
     * @Route("/new", methods={"GET"}, name="bill-types.new")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(BillTypeFormType::class);

        return $this->render('@FlexPHPInvoice/billType/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="bill-types.create")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateBillTypeUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(BillTypeFormType::class);
        $form->handleRequest($request);

        $request = new CreateBillTypeRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'billType'));

        return $this->redirectToRoute('flexphp.invoice.bill-types.index');
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="bill-types.read")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_READ')", statusCode=401)
     */
    public function read(ReadBillTypeUseCase $useCase, string $id): Response
    {
        $request = new ReadBillTypeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->billType->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPInvoice/billType/show.html.twig', [
            'billType' => $response->billType,
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET"}, name="bill-types.edit")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_UPDATE')", statusCode=401)
     */
    public function edit(ReadBillTypeUseCase $useCase, string $id): Response
    {
        $request = new ReadBillTypeRequest($id);

        $response = $useCase->execute($request);

        if (!$response->billType->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(BillTypeFormType::class, $response->billType);

        return $this->render('@FlexPHPInvoice/billType/edit.html.twig', [
            'billType' => $response->billType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="bill-types.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateBillTypeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(BillTypeFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateBillTypeRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'billType'));

        return $this->redirectToRoute('flexphp.invoice.bill-types.index');
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"}, name="bill-types.delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_DELETE')", statusCode=401)
     */
    public function delete(DeleteBillTypeUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteBillTypeRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'billType'));

        return $this->redirectToRoute('flexphp.invoice.bill-types.index');
    }
}
