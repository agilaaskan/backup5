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
namespace Askantech\Communication\Model\ResourceModel\FormData;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection{
	public function _construct(){
		$this->_init("Askantech\Communication\Model\FormData","Askantech\Communication\Model\ResourceModel\FormData");
	}
	protected function _initSelect()
    {
        parent::_initSelect();
        $this->addFieldToFilter('main_table.status', ['neq' => 'deleted']);
        return $this;
    }
}
 ?>