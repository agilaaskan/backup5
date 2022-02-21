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
namespace Askantech\Communication\Model\ResourceModel;
class FormData extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
 public function _construct(){
 $this->_init("askantech_communication_data","id");
 }
}
 ?>