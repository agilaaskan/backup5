<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Sales\Controller\Adminhtml\Order\Create;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Exception\PaymentException;

class Save extends \Magento\Sales\Controller\Adminhtml\Order\Create implements HttpPostActionInterface
{
    /**
     * Saving quote and create order
     *
     * @return \Magento\Framework\Controller\ResultInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $path = 'sales/*/';
        $pathParams = [];

        try {
            // check if the creation of a new customer is allowed
            if (!$this->_authorization->isAllowed('Magento_Customer::manage')
                && !$this->_getSession()->getCustomerId()
                && !$this->_getSession()->getQuote()->getCustomerIsGuest()
            ) {
                return $this->resultForwardFactory->create()->forward('denied');
            }
            $this->_getOrderCreateModel()->getQuote()->setCustomerId($this->_getSession()->getCustomerId());
            $this->_processActionData('save');
            $paymentData = $this->getRequest()->getPost('payment');
            if ($paymentData) {
                $paymentData['checks'] = [
                    \Magento\Payment\Model\Method\AbstractMethod::CHECK_USE_INTERNAL,
                    \Magento\Payment\Model\Method\AbstractMethod::CHECK_USE_FOR_COUNTRY,
                    \Magento\Payment\Model\Method\AbstractMethod::CHECK_USE_FOR_CURRENCY,
                    \Magento\Payment\Model\Method\AbstractMethod::CHECK_ORDER_TOTAL_MIN_MAX,
                    \Magento\Payment\Model\Method\AbstractMethod::CHECK_ZERO_TOTAL,
                ];
                $this->_getOrderCreateModel()->setPaymentData($paymentData);
                $this->_getOrderCreateModel()->getQuote()->getPayment()->addData($paymentData);
            }

            $order = $this->_getOrderCreateModel()
                ->setIsValidate(true)
                ->importPostData($this->getRequest()->getPost('order'))
                ->createOrder();

            $this->_getSession()->clearStorage();
            $this->messageManager->addSuccessMessage(__('You created the order.'));
            if ($this->_authorization->isAllowed('Magento_Sales::actions_view')) {
                $pathParams = ['order_id' => $order->getId()];
                $path = 'sales/order/view';
                 // custom code
                 $corder = $order->getId();
                 if (!empty($corder)) {
                     $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
                     $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                     $connection = $resource->getConnection();
                     //Select Data from magento
                     $sql = "SELECT product_id,weight,qty_ordered,name FROM `sales_order_item` WHERE order_id = '$corder' AND product_type = 'simple'";
                     $result = $connection->fetchAll($sql);
                     foreach($result as $row){
                         if(isset($row["weight"]) && $row["weight"]!=null){
                             $cweight=$row["weight"];
                         }else{
                             $cweight=1;
                         }
                         $cqty = $row["qty_ordered"];
                             $cid = $row["product_id"];
                             $sql1 = "SELECT parent_id FROM `catalog_product_super_link` WHERE product_id = '$cid'";
                             $result1 = $connection->fetchAll($sql1);
                             if(count($result1)>0){
                                 foreach($result1 as $row1){
                                     $cparent = $row1["parent_id"];
                                     $ctotal = $cweight * $cqty;
                                 }
                             }else{
                                 $cparent = $cid;
                                 $ctotal = $cqty;						
             
                             }
                             $sql2 = "SELECT stock_in_hand FROM `ps_custom_stock_management` WHERE m2_product_id = '$cparent' LIMIT 1";
                             $result2 = $connection->fetchAll($sql2);
                             if(count($result2)> 0){
                                 $psqty= $result2[0]['stock_in_hand'];
                                 $newqty = $psqty - $ctotal;
                                 $sql3 = "UPDATE  `ps_custom_stock_management` SET stock_in_hand = $newqty WHERE m2_product_id = '$cparent'";
                                 $connection->query($sql3);  
                             }
                     }
                 }
                 // custom code
            } else {
                $path = 'sales/order/index';
            }

        } catch (PaymentException $e) {
            $this->_getOrderCreateModel()->saveQuote();
            $message = $e->getMessage();
            if (!empty($message)) {
                $this->messageManager->addErrorMessage($message);
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // customer can be created before place order flow is completed and should be stored in current session
            $this->_getSession()->setCustomerId((int)$this->_getSession()->getQuote()->getCustomerId());
            $message = $e->getMessage();
            if (!empty($message)) {
                $this->messageManager->addErrorMessage($message);
            }
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Order saving error: %1', $e->getMessage()));
        }
        return $this->resultRedirectFactory->create()->setPath($path, $pathParams);

    }
}
