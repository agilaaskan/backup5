<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Sales\Model\Order\Pdf\Items\Invoice;

/**
 * Sales Order Invoice Pdf default items renderer
 */
class DefaultInvoice extends \Magento\Sales\Model\Order\Pdf\Items\AbstractItems
{
    /**
     * Core string
     *
     * @var \Magento\Framework\Stdlib\StringUtils
     */
    protected $string;

      /**
     * @var \Magento\Sales\Model\Order\Address\Renderer
     */
    protected $addressRenderer;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Tax\Helper\Data $taxData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->string = $string;
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Draw item line
     *
     * @return void
     */
    public function draw()
    {
        $order = $this->getOrder();
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        // draw Product name
        $lines[0] = [
            [
                // phpcs:ignore Magento2.Functions.DiscouragedFunction
                'text' => $this->string->split(html_entity_decode($item->getName()), 35, true, true),
                'feed' => 35
            ]
        ];

         // custom code
         $taxcheck = 0;

         $corder = $order->getRealOrderId();
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
         $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
         $connection = $resource->getConnection();
         //Select Data from magento
         $sql = "SELECT billing_address FROM `sales_order_grid` WHERE `increment_id` = '$corder'";
         $result = $connection->fetchAll($sql);
            if(count($result)>0){
                foreach($result as $row){
                    $cparent = $row["billing_address"];
                }
            }else{
                $taxcheck = 0;
            }

            if (strpos($cparent, 'puducherry') !== false) {
                $taxcheck = 1;
            } elseif (strpos($cparent, 'Puducherry') !== false) {
                $taxcheck = 1;
            } elseif (strpos($cparent, 'pondicherry') !== false) {
                $taxcheck = 1;
            } elseif (strpos($cparent, 'Pondicherry') !== false) {
                $taxcheck = 1;
            } elseif (strpos($cparent, 'Pondy') !== false) {
                $taxcheck = 1;
            } elseif (strpos($cparent, 'pondy') !== false) {
                $taxcheck = 1;
            } 

         // custom code

        // draw Tax Rate
        $taxPercent = $item->getOrderItem()->getTaxPercent();
        $taxPercent = round($taxPercent);
        $taxPercent = $taxPercent."%";
        $lines[0][] = [
            'text'  => $taxPercent ,
            'feed' => 250,
            'font' => 'bold',
            'align' => 'right',
        ];

        $cgst = $item->getTaxAmount();
        $cgst = "₹".$cgst/2;
        
    if ($taxcheck == 1) {

        // draw CGST
        $lines[0][] = [
            'text'  => $cgst,
            'feed' => 450,
            'font' => 'bold',
            'align' => 'right',
        ];

        // draw SGST
        $lines[0][] = [
            'text' => $cgst,
            'feed' => 495,
            'font' => 'bold',
            'align' => 'right',
        ];

        // draw IGST
        $lines[0][] = [
            'text'  => '0',
            'feed' => 410,
            'font' => 'bold',
            'align' => 'right',
        ];
    } 
    else {
        // draw CGST
        $lines[0][] = [
            'text'  => '0',
            'feed' => 450,
            'font' => 'bold',
            'align' => 'right',
        ];

        // draw SGST
        $lines[0][] = [
            'text' => '0',
            'feed' => 495,
            'font' => 'bold',
            'align' => 'right',
        ];

        // draw IGST
        $lines[0][] = [
            'text'  => $order->formatPriceTxt($item->getTaxAmount()),
            'feed' => 410,
            'font' => 'bold',
            'align' => 'right',
        ];

    }

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 365, 'align' => 'right'];

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
        $feedPrice = 315;
        $feedSubtotal = $feedPrice + 240;
        foreach ($prices as $priceData) {
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedPrice, 'align' => 'right'];
                // draw Subtotal label
                $lines[$i][] = ['text' => $priceData['label'], 'feed' => $feedSubtotal, 'align' => 'right'];
                $i++;
            }
            // draw Price
            $lines[$i][] = [
                'text' => $priceData['price'],
                'feed' => $feedPrice,
                'font' => 'bold',
                'align' => 'right',
            ];
            // draw Subtotal
            $lines[$i][] = [
                'text' => $priceData['subtotal'],
                'feed' => $feedSubtotal,
                'font' => 'bold',
                'align' => 'right',
            ];
            $i++;
        }

        

        // custom options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35,
                ];

                // Checking whether option value is not null
                if ($option['value'] !== null) {
                    if (isset($option['print_value'])) {
                        $printValue = $option['print_value'];
                    } else {
                        $printValue = $this->filterManager->stripTags($option['value']);
                    }
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 30, true, true), 'feed' => 40];
                    }
                }
            }
        }

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }
}
