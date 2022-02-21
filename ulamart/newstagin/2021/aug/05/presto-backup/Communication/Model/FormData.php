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
namespace Askantech\Communication\Model;
class FormData extends \Magento\Framework\Model\AbstractModel{
	public function _construct(){
		$this->_init("Askantech\Communication\Model\ResourceModel\FormData");
	}
}
 ?>