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
 * @author Tyler Schade
 * @copyright Swift Otter Studios, 10/20/16
 * @package default
 **/

class SwiftOtter_AttributeProductUses_Model_Resource_Eav_Entity_Attribute_Option_Value extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('eav/attribute_option', 'option_id');
    }

    public function getValuesForAttribute(Mage_Eav_Model_Entity_Attribute $attribute)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(['main_table' => $this->getMainTable()], ['value' => 'option_id'])
            ->joinLeft(['value' => 'eav_attribute_option_value'], '`value`.option_id = `main_table`.option_id', ['label' => 'value'])
            ->where('`main_table`.attribute_id = ?', $attribute->getId());

        return $this->_getReadAdapter()->fetchAll($select);
    }
}