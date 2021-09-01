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

use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\BillFilterFormType;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\BillFormType;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\CreateBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\DeleteBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillStatusRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillBillTypeRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillOrderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\FindBillProviderRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\IndexBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\ReadBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\UpdateBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\CreateBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\DeleteBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\ExportBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\FindBillBillStatusUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\FindBillBillTypeUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\FindBillBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\FindBillOrderUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\FindBillProviderUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\IndexBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\ReadBillUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase\UpdateBillUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class BillController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexBillUseCase $useCase): Response
    {
        $filters = $this->getFilters($request);

        $template = $request->isXmlHttpRequest()
            ? '@FlexPHPInvoice/bill/_ajax.html.twig'
            : '@FlexPHPInvoice/bill/index.html.twig';

        $request = new IndexBillRequest($filters, (int)$request->query->get('page', 1), 50, $this->getUser()->timezone());

        $response = $useCase->execute($request);

        return $this->render($template, [
            'bills' => $response->bills,
            'filter' => ($this->createForm(BillFilterFormType::class))->createView(),
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        throw $this->createAccessDeniedException();

        $form = $this->createForm(BillFormType::class);

        return $this->render('@FlexPHPInvoice/bill/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateBillUseCase $useCase, TranslatorInterface $trans): Response
    {
        throw $this->createAccessDeniedException();

        $form = $this->createForm(BillFormType::class);
        $form->handleRequest($request);

        $request = new CreateBillRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'bill'));

        return $this->redirectToRoute('flexphp.invoice.bills.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_READ')", statusCode=401)
     */
    public function read(ReadBillUseCase $useCase, int $id): Response
    {
        $request = new ReadBillRequest($id);

        $response = $useCase->execute($request);

        if (!$response->bill->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPInvoice/bill/show.html.twig', [
            'bill' => $response->bill,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_UPDATE')", statusCode=401)
     */
    public function edit(ReadBillUseCase $useCase, int $id): Response
    {
        throw $this->createAccessDeniedException();

        $request = new ReadBillRequest($id);

        $response = $useCase->execute($request);

        if (!$response->bill->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(BillFormType::class, $response->bill);

        return $this->render('@FlexPHPInvoice/bill/edit.html.twig', [
            'bill' => $response->bill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateBillUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        throw $this->createAccessDeniedException();

        $form = $this->createForm(BillFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateBillRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'bill'));

        return $this->redirectToRoute('flexphp.invoice.bills.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_DELETE')", statusCode=401)
     */
    public function delete(DeleteBillUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        throw $this->createAccessDeniedException();

        $request = new DeleteBillRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'bill'));

        return $this->redirectToRoute('flexphp.invoice.bills.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_INDEX')", statusCode=401)
     */
    public function export(Request $request, ExportBillUseCase $useCase): Response
    {
        $filters = $this->getFilters($request);

        $request = new IndexBillRequest($filters, (int)$request->query->get('page', 1), 500, $this->getUser()->timezone());

        $export = $useCase->execute($request);

        $response = new Response(\file_get_contents($export->path));
        $response->headers->set('Content-Type', $export->contentType);
        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $export->filename
        ));

        return $response;
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_ORDER_INDEX')", statusCode=401)
     */
    public function findOrder(Request $request, FindBillOrderUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindBillOrderRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->orders,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_PROVIDER_INDEX')", statusCode=401)
     */
    public function findProvider(Request $request, FindBillProviderUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindBillProviderRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->providers,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLSTATUS_INDEX')", statusCode=401)
     */
    public function findBillStatus(Request $request, FindBillBillStatusUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindBillBillStatusRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->billStatus,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILLTYPE_INDEX')", statusCode=401)
     */
    public function findBillType(Request $request, FindBillBillTypeUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindBillBillTypeRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->billTypes,
            'pagination' => ['more' => false],
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_BILL_INDEX')", statusCode=401)
     */
    public function findBill(Request $request, FindBillBillUseCase $useCase): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse([], Response::HTTP_BAD_REQUEST);
        }

        $request = new FindBillBillRequest($request->request->all());

        $response = $useCase->execute($request);

        return new JsonResponse([
            'results' => $response->bills,
            'pagination' => ['more' => false],
        ]);
    }

    private function getFilters(Request $request): array
    {
        return $request->isMethod('POST')
            ? $request->request->filter('bill_filter_form', [])
            : $request->query->filter('bill_filter_form', []);
    }
}
