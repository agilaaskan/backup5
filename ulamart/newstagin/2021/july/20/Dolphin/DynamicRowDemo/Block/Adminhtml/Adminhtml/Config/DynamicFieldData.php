<?php

namespace Dolphin\DynamicRowDemo\Block\Adminhtml\System\Config;

use Dolphin\DynamicRowDemo\Block\Adminhtml\System\Config\Field\CustomColumn;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;

class DynamicFieldData extends AbstractFieldArray
{
    private $holidaysRenderer;
    private $dateRenderer;
    private $selectOptions;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    protected function _prepareToRender()
    {

        $this->addColumn(
            'select_date',
            [
                'label' => __('Question'),
                'id' => 'select_date',
                'class' => 'required-entry',
                'style' => 'width:300px',
            ]
        );

        $this->addColumn(
            'date_title',
            [
                'label' => __('Answer'),
                'class' => 'required-entry',
                'style' => 'width:300px',
            ]
        );

        $this->addColumn(
            'select_field',
            [
                'label' => __('Option'),
                'renderer' => $this->getSelectFieldOptions(),
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add More');
    }

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $selectFieldData = $row->getSelectField();
        if ($selectFieldData !== null) {
            $options['option_' . $this->getSelectFieldOptions()->calcOptionHash($selectFieldData)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }

    private function getSelectFieldOptions()
    {
        if (!$this->selectOptions) {
            $this->selectOptions = $this->getLayout()->createBlock(
                CustomColumn::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->selectOptions;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = parent::_getElementHtml($element);

        $script = '<script type="text/javascript">
                require(["jquery", "jquery/ui", "mage/calendar"], function (jq) {
                    jq(function(){
                        function bindDatePicker() {
                            setTimeout(function() {
                                jq(".daterecuring").datepicker( { dateFormat: "mm/dd/yy" } );
                            }, 50);
                        }
                        bindDatePicker();
                        jq("button.action-add").on("click", function(e) {
                            bindDatePicker();
                        });
                    });
                });
            </script>';
        $html .= $script;
        return $html;
    }
}