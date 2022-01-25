<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\InvoiceBundle\Domain\Bill\UseCase;

use Domain\Customer\CustomerRepository;
use Domain\Customer\Request\ReadCustomerRequest;
use Domain\Order\OrderRepository;
use Domain\Order\Request\ReadOrderRequest;
use Domain\OrderDetail\OrderDetailRepository;
use Domain\OrderDetail\Request\IndexOrderDetailRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\BillRepository;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Request\IndexBillRequest;
use FlexPHP\Bundle\InvoiceBundle\Domain\Bill\Response\ExportBillResponse;
use FlexPHP\Bundle\NumerationBundle\Domain\Numeration\Numeration;
use FlexPHP\Bundle\NumerationBundle\Domain\Numeration\NumerationRepository;
use FlexPHP\Bundle\NumerationBundle\Domain\Numeration\Request\IndexNumerationRequest;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ExportBillUseCase
{
    private BillRepository $billRepository;

    private OrderRepository $orderRepository;

    private OrderDetailRepository $orderDetailRepository;

    private CustomerRepository $customerRepository;

    private NumerationRepository $numerationRepository;

    public function __construct(
        BillRepository $billRepository,
        OrderRepository $orderRepository,
        OrderDetailRepository $orderDetailRepository,
        CustomerRepository $customerRepository,
        NumerationRepository $numerationRepository
    ) {
        $this->billRepository = $billRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->customerRepository = $customerRepository;
        $this->numerationRepository = $numerationRepository;
    }

    public function execute(IndexBillRequest $request): ExportBillResponse
    {
        $bills = $this->billRepository->findBy($request);

        $numerations = $this->numerationRepository->findBy(new IndexNumerationRequest([
            'isActive' => true,
        ], 1));

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Detalle');

        $sheet->setCellValue('A1', 'TIPO DE COMPROBANTE');
        $sheet->setCellValue('B1', 'PREFIJO');
        $sheet->setCellValue('C1', 'NÚMERO');
        $sheet->setCellValue('D1', 'FECHA');
        $sheet->setCellValue('E1', 'HORA');
        $sheet->setCellValue('F1', 'NIT CLIENTE');
        $sheet->setCellValue('G1', 'NOMBRE O RAZÓN SOCIAL');
        $sheet->setCellValue('H1', 'NÚMERO RESOLUCIÓN');
        $sheet->setCellValue('I1', 'CANTIDAD');
        $sheet->setCellValue('J1', 'DESCRIPCIÓN');
        $sheet->setCellValue('K1', 'VALOR UNITARIO');
        $sheet->setCellValue('L1', 'VALOR TOTAL ANTES DE IVA');
        $sheet->setCellValue('M1', 'IVA');
        $sheet->setCellValue('N1', 'DESCUENTO');
        $sheet->setCellValue('O1', 'VALOR TOTAL FACTURA');
        $sheet->setCellValue('P1', 'BASE DEL IVA');
        $sheet->setCellValue('Q1', 'PORCENTAJE DE IVA');

        $column = 2;

        foreach ($bills as $bill) {
            $order = $this->orderRepository->getById(new ReadOrderRequest($bill->orderId()));
            $customer = $this->customerRepository->getById(new ReadCustomerRequest($order->customerId()));
            $numeration = $this->getResolution($numerations, $bill->prefix());
            $orderDetails = $this->orderDetailRepository->findBy(new IndexOrderDetailRequest([
                'orderId' => $order->id(),
            ], 1, 500));

            foreach ($orderDetails as $orderDetail) {
                $sheet->setCellValue('A' . $column, $bill->typeInstance()->name());
                $sheet->setCellValueExplicit('B' . $column, $bill->prefix(), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('C' . $column, $bill->number(), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('D' . $column, ($bill->createdAt() ? $bill->createdAt()->format('Y-m-d') : ''), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('E' . $column, ($bill->createdAt() ? $bill->createdAt()->format('H:i:s') : ''), DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('F' . $column, $customer->documentNumber(), DataType::TYPE_STRING);
                $sheet->setCellValue('G' . $column, $customer->name());
                $sheet->setCellValueExplicit('H' . $column, $numeration->resolution(), DataType::TYPE_STRING);
                $sheet->setCellValue('I' . $column, $orderDetail->quantity());
                $sheet->setCellValue('J' . $column, $orderDetail->productIdInstance()->name());
                $sheet->setCellValue('K' . $column, $orderDetail->price());
                $sheet->setCellValue('L' . $column, $orderDetail->quantity() * $orderDetail->price());
                $sheet->setCellValue('M' . $column, $orderDetail->tax());
                $sheet->setCellValue('N' . $column, '0');
                $sheet->setCellValue('O' . $column, $orderDetail->total());
                $sheet->setCellValue('P' . $column, $orderDetail->tax() > 0 ? $orderDetail->quantity() * $orderDetail->price() : 0);
                $sheet->setCellValue('Q' . $column, $orderDetail->taxes());
                ++$column;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = \date('YmdHis') . '.xlsx';
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

//         $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
//         $writer->setDelimiter(';');
//         $writer->setEnclosure('"');
//         $writer->setLineEnding("\r\n");
//         $writer->setSheetIndex(0);
//         $filename = \date('YmdHis') . '.csv';
//         $contentType = 'text/plain';

        $path = \tempnam(\sys_get_temp_dir(), $filename);

        $writer->save($path);

        return new ExportBillResponse($path, $filename, $contentType);
    }

    public function getResolution(array $numerations, string $prefix): Numeration
    {
        $numerations = \array_values(\array_filter($numerations, function (Numeration $numeration) use ($prefix) {
            return $numeration->prefix() === $prefix;
        }));

        return $numerations[0] ?? new Numeration;
    }
}
