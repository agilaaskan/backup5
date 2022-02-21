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




$product = $productFactory->create()->getCollection();
$collection = $productFactory->create()->getCollection();

$nsUrl = 'http://base.google.com/ns/1.0';
$xml = new DOMDocument('1.0', 'UTF-8');
$rootNode = $xml->appendChild($xml->createElement('rss'));
$rootNode->setAttribute('version', '2.0');
$rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', $nsUrl);


$xml->formatOutput=true;
$fitness=$xml->createElement("channel");
$rootNode->appendChild($fitness);
$metadata=$xml->createElement("metadata");
$fitness->appendChild($metadata);
$ref_application_id=$xml->createElement("ref_application_id");
$metadata->appendChild($ref_application_id);
$ref_asset_id=$xml->createElement("ref_asset_id");
$metadata->appendChild($ref_asset_id);
$title=$xml->createElement("title","UlaMart - Organic Products | Rice | Millets | Honey | Oil | Soaps |Spices");
$fitness->appendChild($title);
$slink=$xml->createElement("link");
$fitness->appendChild($slink);

    while ($product = $collection->fetchItem()) {
        $productData = $productLoader->create()->load($product->getEntityId());
        if($productData->getStatus() == 1) {
            if($productData->getVisibility() == 4) {
                $cat = $product->getCategoryIds();
                $cat1 = $cat[0];
                if ($cat1 == 2 ){
                    $cat1 = $cat[1];
                }
                $category = $objectManager->create('Magento\Catalog\Model\Category')->load($cat1);
                $fprice = round($productData->getFinalPrice());
                if ($product->getTypeId() == 'simple') {
                    $tprice = $TaxHelper->getTaxPrice($productData, $productData->getFinalPrice(), true);
                    $fprice = round($tprice);

                }
                $descrp = strip_tags($productData->getShortDescription());
                $descrp1 = str_replace('&amp;', '&', $descrp);
                $pdescription = htmlspecialchars($descrp1);
                $ptype = "HOME > ".$category->getName()." > ".$productData->getName();
                    $item=$xml->createElement("item");
                    $fitness->appendChild($item); 

                    $uid=$xml->createElement("g:id",$product->getEntityId());
                    $item->appendChild($uid);

                    $ptitle=$xml->createElement("g:title","<![CDATA[".htmlspecialchars($productData->getName())."]]>");
                    $item->appendChild($ptitle);

                    $descr=$xml->createElement("g:description","<![CDATA[".$pdescription."]]>");
                    $item->appendChild($descr);

                    $link=$xml->createElement("g:link","<![CDATA[".$product->getProductUrl()."]]>");
                    $item->appendChild($link);

                    $condition=$xml->createElement("condition","new");
                    $item->appendChild($condition);

                    $price=$xml->createElement("g:price",$fprice);
                    $item->appendChild($price);

                    $availability=$xml->createElement("g:availability","In Stock");
                    $item->appendChild($availability);

                    $image_link=$xml->createElement("g:image_link","<![CDATA[".$baseUrl."pub/media/catalog/product".$productData->getData('image')."]]>");
                    $item->appendChild($image_link);

                    $gtin=$xml->createElement("g:gtin","");
                    $item->appendChild($gtin);

                    $mpn=$xml->createElement("g:mpn",$product->getEntityId());
                    $item->appendChild($mpn);

                    $brand=$xml->createElement("g:brand","<![CDATA[ ulamart.com ]]>");
                    $item->appendChild($brand);

                    $product_type=$xml->createElement("g:product_type","<![CDATA[".htmlspecialchars($ptype)."]]>");
                    $item->appendChild($product_type);

                    $google_product_category=$xml->createElement("g:google_product_category","");
                    $item->appendChild($google_product_category);
                    
                    $shipping=$xml->createElement("shipping","IN:::0 INR");
                    $item->appendChild($shipping);

            }

        }
    }
   
    $name = strftime('productfeed_%m_%d_%Y.xml');
    header('Content-Disposition: attachment;filename=' . $name);
    header('Content-Type: text/xml');
    $xml->formatOutput = TRUE;
    echo $xml->saveXML();
    // echo "<xmp>".$xml->saveXML()."</xmp>";

    $xml->save("productreport.xml");