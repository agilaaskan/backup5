<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Checkout\Controller\Onepage;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

/**
 * Onepage checkout success controller class
 */
class Success extends \Magento\Checkout\Controller\Onepage implements HttpGetActionInterface
{
    /**
     * Order success action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $session = $this->getOnepage()->getCheckout();
        if (!$this->_objectManager->get(\Magento\Checkout\Model\Session\SuccessValidator::class)->isValid()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }
        $session->clearQuote();
        //@todo: Refactor it to match CQRS
        $resultPage = $this->resultPageFactory->create();
        $this->_eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            [
                'order_ids' => [$session->getLastOrderId()],
                'order' => $session->getLastRealOrder()
            ]
        );
        // custom code
        $corder = $session->getLastOrderId();
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
            $ctotal = $cweight * $cqty;
                $cid = $row["product_id"];
                $sql1 = "SELECT parent_id FROM `catalog_product_super_link` WHERE product_id = '$cid'";
                $result1 = $connection->fetchAll($sql1);
                if(count($result1)>0){
                    foreach($result1 as $row1){
                        $cparent = $row1["parent_id"];
                    }
                }else{
                    $cparent = $cid;

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
        // custom code
        return $resultPage;
    }
}
