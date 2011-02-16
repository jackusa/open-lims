<?php
/**
 * @package data
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
 * Data Entity Has Data Entity Access Class
 * @package data
 */
class DataEntityHasDataEntity_Access
{
	const DATA_ENTITY_HAS_DATA_ENTITY_TABLE = 'core_data_entity_has_data_entities';

	private $data_entity_pid;
	private $data_entity_cid;

	/**
	 * @param integer $primary_key
	 */
	function __construct($data_entity_pid, $data_entity_cid)
	{
		global $db;
			
		if (is_numeric($data_entity_pid) and is_numeric($data_entity_cid))
		{
			$sql = "SELECT * FROM ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." WHERE data_entity_pid = ".$data_entity_pid." AND data_entity_cid = ".$data_entity_cid."";
			$res = $db->db_query($sql);			
			$data = $db->db_fetch_assoc($res);
			
			if ($data[data_entity_pid])
			{
				$this->data_entity_pid	= $data[data_entity_pid];
				$this->data_entity_cid	= $data[data_entity_cid];
			}
			else
			{
				$this->data_entity_pid	= null;
				$this->data_entity_cid	= null;
			}
		}
		else
		{
			$this->data_entity_pid	= null;
			$this->data_entity_cid	= null;
		}
	}
	
	function __destruct()
	{
		if ($this->data_entity_pid)
		{
			unset($this->data_entity_pid);
			unset($this->data_entity_cid);
		}
	}
	
	/**
	 * @param integer $data_entity_pid
	 * @param integer $data_entity_cid
	 * @return true
	 */
	public function create($data_entity_pid, $data_entity_cid)
	{
		global $db;
		
		if (is_numeric($data_entity_pid) and is_numeric($data_entity_cid))
		{
			$sql_write = "INSERT INTO ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." (data_entity_pid,data_entity_cid) " .
					"VALUES (".$data_entity_pid.",".$data_entity_cid.")";
			$res_write = $db->db_query($sql_write);
			
			if ($db->db_affected_rows($res_write) == 1)
			{
				return true;
			}
			else
			{
				return false;
			}	
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * @return bool
	 */
	public function delete()
	{
		global $db;
		
		if ($this->data_entity_pid)
		{		
			$sql = "DELETE FROM ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." WHERE data_entity_pid = ".$this->data_entity_pid." AND data_entity_cid = ".$this->data_entity_cid."";
			$res = $db->db_query($sql);
			
			if ($db->db_affected_rows($res) == 1)
			{
				$this->__destruct();
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;	
		}
	}
	

	/**
	 * @param integer $data_entity_pid
	 * @return array
	 */
	public static function list_data_entity_cid_by_data_entity_pid($data_entity_pid)
	{
		global $db;
			
		if (is_numeric($data_entity_pid))
		{
			$return_array = array();
			
			$sql = "SELECT data_entity_cid FROM ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." WHERE data_entity_pid = ".$data_entity_pid."";
			$res = $db->db_query($sql);
			
			while ($data = $db->db_fetch_assoc($res))
			{
				array_push($return_array,$data[data_entity_cid]);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $data_entity_cid
	 * @return array
	 */
	public static function list_data_entity_pid_by_data_entity_cid($data_entity_cid)
	{
		global $db;
			
		if (is_numeric($data_entity_cid))
		{
			$return_array = array();
			
			$sql = "SELECT data_entity_pid FROM ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." WHERE data_entity_cid = ".$data_entity_cid."";
			$res = $db->db_query($sql);
			
			while ($data = $db->db_fetch_assoc($res))
			{
				array_push($return_array,$data[data_entity_pid]);
			}
			
			if (is_array($return_array))
			{
				return $return_array;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * @param integer $data_entity_pid
	 * @return bool
	 */
	public static function delete_by_data_entity_cid($data_entity_cid)
	{
		global $db;
			
		if (is_numeric($data_entity_cid))
		{
			$return_array = array();
			
			$sql = "DELETE FROM ".self::DATA_ENTITY_HAS_DATA_ENTITY_TABLE." WHERE data_entity_cid = ".$data_entity_cid."";
			$res = $db->db_query($sql);
			
			if ($res !== false)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}
?>