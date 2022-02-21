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
namespace Askantech\Communication\Controller\Customer;  
class Index extends \Magento\Framework\App\Action\Action { 
public function execute() { 
$this->_view->loadLayout(); 
$this->_view->renderLayout(); 
} 
} 
?>
