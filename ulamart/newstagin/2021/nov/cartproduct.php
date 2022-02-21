<?php

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;


require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
// $cacheManager = $objectManager->create('Magento\Framework\App\Cache\Manager');
// $cacheManager->flush($cacheManager->getAvailableTypes());

$objectManager->get('Magento\Framework\App\State')->setAreaCode('frontend');
$TaxHelper = $objectManager->get('Magento\Catalog\Helper\Data');
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');

$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
$itemsCollection = $cart->getQuote()->getItemsCollection();
$itemsVisible = $cart->getQuote()->getAllVisibleItems();
    if(count($itemsVisible)) {
        foreach($itemsVisible as $item) {
        // echo 'ID: '.$item->getProductId().'<br />';
        // echo 'Name: '.$item->getName().'<br />';
        // echo 'Sku: '.$item->getSku().'<br />';
        // echo 'Quantity: '.$item->getQty().'<br />';
        // echo 'Price: '.$item->getPrice().'<br />';
        // echo "<br />";            
    }   
}


?>