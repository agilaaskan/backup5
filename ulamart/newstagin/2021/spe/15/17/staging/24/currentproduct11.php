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
        if($productData->getVisibility() == 4) {
            $cat = $product->getCategoryIds();
            $cat1 = $cat[0];
            if ($cat1 == 2 ){
                $cat1 = $cat[1];
            }
            $tax = 0;
            $category = $objectManager->create('Magento\Catalog\Model\Category')->load($cat1);
            if ($product->getTypeId() == 'simple') {
                //Select Data from magento
                $productid = $product->getEntityId();
                $sql = "SELECT tax_calculation_rate.rate FROM tax_calculation INNER JOIN catalog_product_index_price ON catalog_product_index_price.tax_class_id = tax_calculation.product_tax_class_id INNER JOIN  tax_calculation_rate ON tax_calculation.tax_calculation_rate_id = tax_calculation_rate.tax_calculation_rate_id WHERE catalog_product_index_price.entity_id = $productid LIMIT 1";
                $result = $connection->fetchAll($sql);
                if(count($result)> 0){
                    $tax = $result[0]['rate'];
                }
            }
            // custom code
            $content[] = [$product->getEntityId(),
                $productData->getName(),
                strip_tags($productData->getShortDescription()),
                $product->getProductUrl(),
                "new",
                round($tax + $productData->getFinalPrice()),
                "In Stock",
                $baseUrl."pub/media/catalog/product".$productData->getData('image'),
                "",
                $product->getEntityId(),
                "ulamart.com",
                "HOME > ".$category->getName()." > ".$productData->getName(),
                "",
                "IN:::0 INR"];
        }

    }
}
$csvProcessor->setEnclosure('"')->setDelimiter(',')->saveData($filePath, $content);
$fileFactory->create($fileName, ['type' => "filename",
    'value' => $fileName,
    'rm' => true,
    // True => File will be remove from directory after download.
], DirectoryList::MEDIA, 'text/xls', null);