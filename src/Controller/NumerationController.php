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

use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\NumerationFormType;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\CreateNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\DeleteNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\IndexNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\ReadNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\Request\UpdateNumerationRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase\CreateNumerationUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase\DeleteNumerationUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase\IndexNumerationUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase\ReadNumerationUseCase;
use FlexPHP\Bundle\InvoiceBundle\Domain\Numeration\UseCase\UpdateNumerationUseCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class NumerationController extends AbstractController
{
    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_INDEX')", statusCode=401)
     */
    public function index(Request $request, IndexNumerationUseCase $useCase): Response
    {
        $template = $request->isXmlHttpRequest()
            ? '@FlexPHPInvoice/numeration/_ajax.html.twig'
            : '@FlexPHPInvoice/numeration/index.html.twig';

        $request = new IndexNumerationRequest($request->request->all(), (int)$request->query->get('page', 1));

        $response = $useCase->execute($request);

        return $this->render($template, [
            'numerations' => $response->numerations,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_CREATE')", statusCode=401)
     */
    public function new(): Response
    {
        $form = $this->createForm(NumerationFormType::class);

        return $this->render('@FlexPHPInvoice/numeration/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_CREATE')", statusCode=401)
     */
    public function create(Request $request, CreateNumerationUseCase $useCase, TranslatorInterface $trans): Response
    {
        $form = $this->createForm(NumerationFormType::class);
        $form->handleRequest($request);

        $request = new CreateNumerationRequest($form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.created', [], 'numeration'));

        return $this->redirectToRoute('flexphp.invoice.numerations.index');
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_READ')", statusCode=401)
     */
    public function read(ReadNumerationUseCase $useCase, int $id): Response
    {
        $request = new ReadNumerationRequest($id);

        $response = $useCase->execute($request);

        if (!$response->numeration->id()) {
            throw $this->createNotFoundException();
        }

        return $this->render('@FlexPHPInvoice/numeration/show.html.twig', [
            'numeration' => $response->numeration,
        ]);
    }

    /**
     * @Cache(smaxage="3600")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_UPDATE')", statusCode=401)
     */
    public function edit(ReadNumerationUseCase $useCase, int $id): Response
    {
        $request = new ReadNumerationRequest($id);

        $response = $useCase->execute($request);

        if (!$response->numeration->id()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(NumerationFormType::class, $response->numeration);

        return $this->render('@FlexPHPInvoice/numeration/edit.html.twig', [
            'numeration' => $response->numeration,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_UPDATE')", statusCode=401)
     */
    public function update(Request $request, UpdateNumerationUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $form = $this->createForm(NumerationFormType::class);
        $form->submit($request->request->get($form->getName()));
        $form->handleRequest($request);

        $request = new UpdateNumerationRequest($id, $form->getData(), $this->getUser()->id());

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.updated', [], 'numeration'));

        return $this->redirectToRoute('flexphp.invoice.numerations.index');
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER_NUMERATION_DELETE')", statusCode=401)
     */
    public function delete(DeleteNumerationUseCase $useCase, TranslatorInterface $trans, int $id): Response
    {
        $request = new DeleteNumerationRequest($id);

        $useCase->execute($request);

        $this->addFlash('success', $trans->trans('message.deleted', [], 'numeration'));

        return $this->redirectToRoute('flexphp.invoice.numerations.index');
    }
}
