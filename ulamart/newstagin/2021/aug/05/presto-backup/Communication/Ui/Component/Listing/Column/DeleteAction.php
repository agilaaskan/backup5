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
namespace Askantech\Communication\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class DeleteAction extends Column
{
    const URL_PATH_DELETE = 'askantech_communication/grid/delete';
   public $urlBuilder;

   public $layout;
   protected $formKey;
   public function __construct(
       ContextInterface $context,
       UiComponentFactory $uiComponentFactory,
       UrlInterface $urlBuilder,
       \Magento\Framework\View\LayoutInterface $layout,
       \Magento\Framework\Data\Form\FormKey $formKey,
       array $components = [],
       array $data = []
   ) {
       	$this->urlBuilder = $urlBuilder;
           $this->layout = $layout;
           $this->formKey = $formKey;
       	parent::__construct($context, $uiComponentFactory, $components, $data);
   }


   public function getViewUrl()
   {
       return $this->urlBuilder->getUrl(
           $this->getData('config/urlid')
       );
   }
  
   public function prepareDataSource(array $dataSource)
   {
       if (isset($dataSource['data']['items'])) {
           foreach ($dataSource['data']['items'] as & $item) {
               if (isset($item['id'])) {

                   $item[$this->getData('name')] = html_entity_decode('
                   <form id="myform'.$item['id'].'" class="del_form" action="'.$this->urlBuilder->getUrl('askantech_communication/grid/delete').'" method="post">
                <input name="form_key" type="hidden" value="'.$this->formKey->getFormKey().'" />
                    <input type="hidden" name="id" value="'.$item['id'].'">
                    <input type="hidden" name="admin_read_status" value="'.$item['admin_read_status'].'">
                    <input class="submit" type="submit" onClick="return confirmSubmit()" value="Delete" style="border: 1px solid #adadad;background: #e3e3e3;color: #000000;height: 30px;width: 80px;">
                </form><script>function confirmSubmit()
{
var agree=confirm("Are you sure you want to delete the conversation?");
if (agree)
 return true ;
else
 return false ;
}
</script>
<script>
require(["jquery"], function () {
jQuery(document).ready(function() {
var status=jQuery("#myform'.$item['id'].' input[name=admin_read_status]").val();
if(status == 0){
jQuery("#myform'.$item['id'].'").parents("tr.data-row").addClass("bold");
}
});
});
</script>
<style>tr.data-row.bold{font-weight:bold;}</style>
');
               }
           }
       }

       return $dataSource;
   }
}

