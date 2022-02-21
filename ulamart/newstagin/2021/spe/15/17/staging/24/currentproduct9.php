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

$content[] = ['entity_id' => __('Entity ID'),
    'name' => __('Product Name'),
    'short_description' => __('Description'),
    'url_path' => __('Url'),
    'condition' => __('Condition'),
    'price' => __('Price'),
    'availability' => __('Availability'),
    'image' => __('Image url'),
    'gtin' => __('gtin'),
    'mpn' => __('mpn'),
    'brand' => __('Brand'),
    'product_type' => __('Product Type'),
    'google_product_category' => __('Google Product Category'),
    'shipping' => __('Shipping'),];


$product = $productFactory->create()->getCollection();
$collection = $productFactory->create()->getCollection();
$fileName = 'product_excel.xls'; // Add Your CSV File name
$filePath = $directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
while ($product = $collection->fetchItem()) {
    $productData = $productLoader->create()->load($product->getEntityId());
    if($productData->getStatus() == 1) {
    $content[] = [$product->getEntityId(),
        $productData->getName(),
        $product->getShortDescription(),
        $product->getProductUrl(),
        "new",
        $product->getFinalPrice(),
        "In Stock",
        $product->getData('image'),
        "",
        $product->getEntityId(),
        "ulamart.com",
        "",
        "",
        "IN:::0 INR"];

    }
}
$csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
$fileFactory->create($fileName, ['type' => "filename",
    'value' => $fileName,
    'rm' => true,
    // True => File will be remove from directory after download.
], DirectoryList::MEDIA, 'text/xls', null);