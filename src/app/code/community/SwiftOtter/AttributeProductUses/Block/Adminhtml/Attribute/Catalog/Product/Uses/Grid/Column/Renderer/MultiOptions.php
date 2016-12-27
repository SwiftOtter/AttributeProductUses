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

class SwiftOtter_AttributeProductUses_Block_Adminhtml_Attribute_Catalog_Product_Uses_Grid_Column_Renderer_MultiOptions extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options
{
    /**
     * Based on Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Options.
     * The purpose of this renderer is to be able to render more than one option selection.
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        /** @var Mage_Adminhtml_Block_Widget_Grid_Column $column */
        $column = $this->getColumn();
        $options = $column->getData('options');
        $showMissingOptionValues = (bool) $column->getData('show_missing_option_values');
        if (!empty($options) && is_array($options)) {
            $value = $row->getData($column->getData('index'));

            if((int) strpos($value, ',') >= 0) {
                $value = explode(',', $value);
            }

            if (is_array($value)) {
                $res = array_map(function ($item) use ($showMissingOptionValues, $options) {
                    if (isset($options[$item])) {
                        return $this->escapeHtml($options[$item]);
                    } elseif ($showMissingOptionValues) {
                        return $this->escapeHtml($item);
                    }
                }, $value);

                return implode(', ', $res);
            } elseif (isset($options[$value])) {
                return $this->escapeHtml($options[$value]);
            } elseif (in_array($value, $options)) {
                return $this->escapeHtml($value);
            }
        }

        return '';
    }
}