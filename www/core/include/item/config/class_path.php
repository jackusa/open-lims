<?php 
/**
 * @package item
 * @version 0.4.0.0
 * @author Roman Konertz
 * @copyright (c) 2008-2010 by Roman Konertz
 * @license GPLv3
 * 
 * This file is part of Open-LIMS
 * Available at http://www.open-lims.org
 * 
 * This program is free software;
 * you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation;
 * version 3 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 
 */
	$classes['ItemListenerInterface']		= $path_prefix."core/include/item/interfaces/item_listener.interface.php";

	$classes['ItemUnlinkEvent']				= $path_prefix."core/include/item/events/item_unlink_event.class.php";
	
	$classes['Item']						= $path_prefix."core/include/item/item.class.php";
	$classes['ItemClass']					= $path_prefix."core/include/item/item_class.class.php";
	$classes['ItemInformation']				= $path_prefix."core/include/item/item_information.class.php";
?>