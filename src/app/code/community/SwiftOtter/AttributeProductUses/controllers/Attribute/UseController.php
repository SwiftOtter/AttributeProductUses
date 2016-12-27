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

class SwiftOtter_AttributeProductUses_Attribute_UseController extends Mage_Adminhtml_Controller_Action
{
    public function productAction()
    {
        $attribute = $this->_initAttribute();
        if ($attribute->getId()) {
            $this->loadLayout();
            $this->renderLayout();
        }
    }

    public function productGridAction()
    {
        $attribute = $this->_initAttribute();
        if ($attribute->getId()) {
            $this->loadLayout();
            $this->renderLayout();
        }
    }

    protected function _initAttribute()
    {
        $request = $this->getRequest();
        $attributeId = (int)$request->getParam('attribute_id');
        $attribute = Mage::getModel('eav/entity_attribute');
        Mage::register('entity_attribute', $attribute);

        if ($attributeId > 0) {
            $attribute->load($attributeId);
        }
        return $attribute;
    }
}