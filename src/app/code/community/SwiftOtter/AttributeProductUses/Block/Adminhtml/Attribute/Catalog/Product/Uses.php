<?php
/**
 *
 *
 * @author Joseph Maxwell
 * @copyright Swift Otter Studios, 1/31/14
 * @package default
 **/

class SwiftOtter_AttributeProductUses_Block_Adminhtml_Attribute_Catalog_Product_Uses extends Mage_Adminhtml_Block_Widget_Grid_Container
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'Adminhtml_Attribute_Catalog_Product_Uses';
        $this->_blockGroup = 'SwiftOtter_AttributeProductUses';
        $this->_headerText = Mage::helper('SwiftOtter_AttributeProductUses')->__('Attribute Used in Products:');

        $this->removeButton('add');
    }

    protected function _prepareLayout()
    {
        if (Mage::registry('entity_attribute')) {
            $block = $this->getLayout()->createBlock($this->_blockGroup . '/' . $this->_controller . '_Grid', $this->_controller . '.Grid');
            $this->setChild('grid', $block->setSaveParametersInSession(true));
        }
    }

    public function getTabLabel()
    {
        return $this->__('Product Uses');
    }

    public function getTabTitle()
    {
        return $this->__('Product Uses');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getTabClass()
    {
        return 'ajax product-uses-tab';
    }

    public function getSkipGenerateContent()
    {
        return true;
    }

    public function getTabUrl()
    {
        if ($attribute = Mage::registry('entity_attribute')) {
            $attributeId = $attribute->getId();
        } else {
            $attributeId = 0;
        }

        return Mage::getUrl('*/attribute_use/product', array('attribute_id' => $attributeId));
    }

}