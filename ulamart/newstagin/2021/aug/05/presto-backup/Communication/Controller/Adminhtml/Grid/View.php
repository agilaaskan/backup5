<?php
/**
 * Askan Technology
 *
 * @category  AskanTech
 * @package   Askantech_Communication
 * @author    Askan
 * @copyright Copyright (c) AskanTech (https://www.askantech.com/)
 * @license   https://www.askantech.com/Communication/LICENSE.txt
 */
namespace Askantech\Communication\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class View extends Action
{
   protected $resultRawFactory;
   protected $layoutFactory;

   public function __construct(
       Context $context,
       \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
       \Magento\Framework\View\LayoutFactory $layoutFactory
   ) {
       $this->resultRawFactory = $resultRawFactory;
       $this->layoutFactory = $layoutFactory;
       parent::__construct($context);
   }

   public function execute()
   {
       $content = $this->layoutFactory->create()
           ->createBlock(
               \Askantech\Communication\Block\Adminhtml\View::class
           );

       /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
       $resultRaw = $this->resultRawFactory->create();
       return $resultRaw->setContents($content->toHtml());
   }
}