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

class ViewAction extends Column
{
    const URL_PATH_DELETE = 'askantech_communication/grid/delete';
   public $urlBuilder;

   public $layout;

   public function __construct(
       ContextInterface $context,
       UiComponentFactory $uiComponentFactory,
       UrlInterface $urlBuilder,
       \Magento\Framework\View\LayoutInterface $layout,
       array $components = [],
       array $data = []
   ) {
       	$this->urlBuilder = $urlBuilder;
      	 $this->layout = $layout;
       	parent::__construct($context, $uiComponentFactory, $components, $data);
   }

   public function getViewUrl()
   {
       return $this->urlBuilder->getUrl(
           $this->getData('config/viewUrlPath')
       );
   }
  
   public function prepareDataSource(array $dataSource)
   {
       if (isset($dataSource['data']['items'])) {
           foreach ($dataSource['data']['items'] as & $item) {
               if (isset($item['id'])) {
                   $item[$this->getData('name')] = $this->layout->createBlock(
                       \Magento\Backend\Block\Widget\Button::class,
                       '',
                       [
                           'data' => [
                               'label' => __('View'),
                               'type' => 'button',
                               'disabled' => false,
                               'class' => 'askantech-communication-view',
                               'onclick' => 'askanView.open(\''. $this->getViewUrl().'?id='.$item['id'].'\', \''.$item['id'].'\', \''.$item['subject'].'\', \''.$item['querry_id'].'\')',
                           ]
                        ]
                   )->toHtml();
               }
           }
       }

       return $dataSource;
   }
}