<?php

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\Filesystem\DirectoryList;


require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();

$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$objectManager->get('Magento\Framework\App\State')->setAreaCode('frontend');
$productLoader = $objectManager->get('Magento\Catalog\Model\ProductFactory');
$fileFactory = $objectManager->get('Magento\Framework\App\Response\Http\FileFactory');
$productFactory = $objectManager->get('Magento\Catalog\Model\ProductFactory');
$layoutFactory = $objectManager->get('Magento\Framework\View\Result\LayoutFactory');
$csvProcessor = $objectManager->get('Magento\Framework\File\Csv');
$directoryList = $objectManager->get('Magento\Framework\App\Filesystem\DirectoryList');
$TaxHelper = $objectManager->get('Magento\Catalog\Helper\Data');
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
$connection = $resource->getConnection();
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
$baseUrl = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
header("Cache-Control: no-cache, must-revalidate");

?>
        <div id="#checking"> <?php
        clearstatcache();
$cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
$itemsCollection = $cart->getQuote()->getItemsCollection();
$itemsVisible = $cart->getQuote()->getAllVisibleItems();
    if(count($itemsVisible)) {
        foreach($itemsVisible as $item) { 
        echo 'ID: '.$item->getProductId().'<br />';
        echo 'Name: '.$item->getName().'<br />';
        echo 'Sku: '.$item->getSku().'<br />';
        echo 'Quantity: '.$item->getQty().'<br />';
        echo 'Price: '.$item->getPrice().'<br />';
        echo "<br />";        
    }   
}
?>
</div>  
<script>
require([ "jquery" ], function($) {
        $("#checking").load(location.href+" #checking>*","");
});
</script>