<?php
/**
 * SwiftOtter_Base is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftOtter_Base is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with SwiftOtter_Base. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright: 2013 (c) SwiftOtter Studios
 *
 * @author Joseph Maxwell
 * @copyright Swift Otter Studios, 7/3/15
 * @package default
 **/

class SwiftOtter_AttributeProductUses_Block_Adminhtml_Attribute_Catalog_Product_Uses_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected $_attribute;
    protected $resourceHelper;
    protected $optionResource;

    public function __construct(
        $resourceHelper = null,
        $optionResource = null
    ){
        $this->resourceHelper = $resourceHelper;
        $this->optionResource = $optionResource;

        parent::__construct();
        $this->setId('product_grid')
            ->setDefaultSort('id')
            ->setDefaultDir('desc')
            ->setSaveParametersInSession(true);
    }

    public function getAttribute()
    {
        if (!$this->_attribute) {
            $this->_attribute = Mage::registry('entity_attribute');

            if (!$this->_attribute) {
                $this->_attribute = Mage::getModel('eav/entity_attribute');
            }
        }
        return $this->_attribute;
    }

    public function getGridUrl()
    {
        return Mage::getUrl('*/attribute_use/productGrid', array('attribute_id' => $this->getAttribute()->getId()));
    }

    protected function _prepareCollection()
    {
        $this->_removeMassAction();

        /** @var Mage_Eav_Model_Entity_Attribute $attribute */
        if ($attribute = Mage::registry('entity_attribute')) {
            $collection = Mage::getResourceModel('SwiftOtter_AttributeProductUses/Product_UsesCollection')->getUsedProducts($attribute->getAttributeCode());
            $this->setCollection($collection);
        }

        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }

    protected function _removeMassAction()
    {
        $this->removeColumn('massaction');

        $massAction = $this->getMassactionBlock();
        foreach ($massAction->getItems() as $item) {
            $massAction->removeItem($item->getId());
        }

        return $this;
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->removeColumn('qty');
        $this->removeColumn('price');
        $this->getColumn('action')->setActions(
            $this->_changeProductUrl($this->getColumn('action')->getActions())
        );

        $attribute = $this->getAttribute();
        if ($attribute->getId()) {
            $properties = [
                'header'=> $attribute->getFrontendLabel(),
                'width' => '60px',
                'index' => $attribute->getAttributeCode()
            ];

            if ($attribute->getSourceModel() || $attribute->getFrontendInput() == 'select' || $attribute->getFrontendInput() == 'multiselect') {
                $properties['type'] = 'options';
                $properties['options'] = $this->getOptions($attribute);
                $properties['filter_condition_callback'] = [$this, 'filterMultiselectCallback'];
                $properties['renderer'] = 'SwiftOtter_AttributeProductUses/Adminhtml_Attribute_Catalog_Product_Uses_Grid_Column_Renderer_MultiOptions';
            } else {
                switch ($attribute->getBackendType()) {
                    case 'static':
                        $type = 'text';
                        break;
                    case 'datetime':
                        $type = 'datetime';
                        break;
                    case 'varchar':
                        $type = 'text';
                        break;
                    case 'int':
                        $type = 'number';
                        break;
                    case 'float':
                        $type = 'number';
                        break;
                    default:
                        $type = 'text';
                        break;
                }

                $properties['type'] = $type;
            }

            $this->addColumnAfter($attribute->getAttributeCode(), $properties, 'name');
        }

        return $this;
    }

    protected function getOptions(Mage_Eav_Model_Entity_Attribute $attribute)
    {
        if ($attribute->getData('source_model')) {
            return $this->flattenOptions($attribute->getSource()->getAllOptions());
        } else {
            return $this->flattenOptions($this->getOptionResource()->getValuesForAttribute($attribute));
        }
    }

    /**
     * @param $options
     * @return array
     */
    protected function flattenOptions(array $options)
    {
        $filteredOptions = array_filter($options, function ($option) {
            return (bool) $option['label'];
        });

        $flattenedOptions = array_reduce($filteredOptions, function ($carry, $item) {
            $carry[$item['value']] = $item['label'];

            return $carry;
        }, []);

        return $flattenedOptions;
    }

    /**
     * @param Mage_Eav_Model_Entity_Collection_Abstract $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     */
    protected function filterMultiselectCallback(Mage_Eav_Model_Entity_Collection_Abstract $collection, Mage_Adminhtml_Block_Widget_Grid_Column $column)
    {
        $likeExpression = Mage::getResourceHelper('core')->addLikeEscape($column->getFilter()->getValue(), [ 'position' => 'any' ]);

        if ($likeExpression) {
            $collection->addFieldToFilter($column->getId(), [ 'like' => $likeExpression ]);
        }
    }

    protected function _changeProductUrl($urlArray)
    {
        return array_map(function($item) {
            if (isset($item["url"]["base"])) {
                $item["url"]["base"] = "*/catalog_product/edit";
            };

            return $item;
        }, $urlArray);
    }

    /**
     * @return SwiftOtter_Base_Model_Resource_Helper
     */
    protected function getResourceHelper()
    {
        if (!$this->resourceHelper) {
            $this->resourceHelper = Mage::getResourceModel('SwiftOtter_Base/Helper');
        }

        return $this->resourceHelper;
    }

    protected function getOptionResource()
    {
        if (!$this->optionResource) {
            $this->optionResource = Mage::getResourceModel('SwiftOtter_AttributeProductUses/Eav_Entity_Attribute_Option_Value');
        }

        return $this->optionResource;
    }
}