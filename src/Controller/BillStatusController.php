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

use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\BillStatusFormType;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\CreateBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\DeleteBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\IndexBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\ReadBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\Request\UpdateBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\CreateBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\DeleteBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\IndexBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\ReadBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\BillStatus\UseCase\UpdateBillStatusUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/bill-status")
 */
final class BillStatusController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="bill-status.index")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexBillStatusUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest() ? 'billStatus/_ajax.html.twig' : 'billStatus/index.html.twig';

        $request = new IndexBillStatusRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'billStatus' => $response->billStatus,
        ]);
    }

    /**
     * @Route("/new", methods={"GET"}, name="bill-status.new")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(BillStatusFormType::class);

        return $this->render('billStatus/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", methods={"POST"}, name="bill-status.create")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateBillStatusUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(BillStatusFormType::class);
        $form->handleRequest($request);

        $request = new CreateBillStatusRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'billStatus'));

        return $this->redirectToRoute('bill-status.index');
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="bill-status.read")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_READ')", statusCode=401)
     */
    public function read(ReadBillStatusUseCase $useCase, string $id): Response
    {
        $request = new ReadBillStatusRequest($id);

        $response = $useCase->execute($request);

        if (!$response->billStatus->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('billStatus/show.html.twig', [
            'billStatus' => $response->billStatus,
        ]);
    }

    /**
     * @Route("/edit/{id}", methods={"GET"}, name="bill-status.edit")
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_UPDATE')", statusCode=401)
     */
    public function edit(ReadBillStatusUseCase $useCase, string $id): Response
    {
        $request = new ReadBillStatusRequest($id);

        $response = $useCase->execute($request);

        if (!$response->billStatus->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(BillStatusFormType::class, $response->billStatus);

        return $this->render('billStatus/edit.html.twig', [
            'billStatus' => $response->billStatus,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", methods={"PUT"}, name="bill-status.update")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateBillStatusUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $form = $this->createForm(BillStatusFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateBillStatusRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'billStatus'));

        return $this->redirectToRoute('bill-status.index');
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"}, name="bill-status.delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_DELETE')", statusCode=401)
     */
    public function delete(DeleteBillStatusUseCase $useCase, TranslatorInterface $trans, string $id): Response
    {
        $request = new DeleteBillStatusRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'billStatus'));

        return $this->redirectToRoute('bill-status.index');
    }
}
