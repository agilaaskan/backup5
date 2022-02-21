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

$content[] = ['name' => __('Product Name'),
    'entity_id' => __('Entity ID'),
    'attribute_set_id' => __('Attribute Set ID'),
    'type_id' => __('Type ID'),
    'sku' => __('Sku'),
    'required_options' => __('Required Options'),
    'created_at' => __('Created At'),
    'updated_at' => __('Updated At'),];


$product = $productFactory->create()->getCollection();
$collection = $productFactory->create()->getCollection();
$fileName = 'product_excel.xls'; // Add Your CSV File name
$filePath = $directoryList->getPath(DirectoryList::MEDIA) . "/" . $fileName;
while ($product = $collection->fetchItem()) {
    $productData = $productLoader->create()->load($product->getEntityId());
    $content[] = [$productData->getName(),
        $product->getEntityId(),
        $product->getAttributeSetId(),
        $product->getTypeId(),
        $product->getSku(),
        $product->getRequiredOptions(),
        $product->getCreatedAt(),
        $product->getUpdatedAt()];
}
$csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
$fileFactory->create($fileName, ['type' => "filename",
    'value' => $fileName,
    'rm' => true,
    // True => File will be remove from directory after download.
], DirectoryList::MEDIA, 'text/xls', null);