<?php
/**
 * @package base
 * @version 0.4.0.0
 * @author Roman Konertz <konertz@open-lims.org>
 * @copyright (c) 2008-2011 by Roman Konertz
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
 * System Log Admin IO Class
 * @package base
 */
class AdminSystemLogIO
{
	public static function home()
	{
		if ($_GET[runid])
		{
			$type_id = $_GET[runid];
		}
		else
		{
			$type_id = 1;
		}
		
		$list = new ListStat_IO(SystemFE_Wrapper::count_list_system_log($type_id), 20);
		
		$list->add_column("User", "user", true, null);
		$list->add_column("Date/Time", "datetime", true, null);
		$list->add_column("IP", "ip", true, null);
		$list->add_column("Info", "info", true, null);
		$list->add_column("File", "file", true, null);
				
		if ($_GET[page])
		{
			if ($_GET[sortvalue] and $_GET[sortmethod])
			{
				$result_array = SystemFE_Wrapper::list_system_log($type_id, $_GET[sortvalue], $_GET[sortmethod], ($_GET[page]*20)-20, ($_GET[page]*20));
			}
			else
			{
				$result_array = SystemFE_Wrapper::list_system_log($type_id, null, null, ($_GET[page]*20)-20, ($_GET[page]*20));
			}				
		}
		else
		{
			if ($_GET[sortvalue] and $_GET[sortmethod])
			{
				$result_array = SystemFE_Wrapper::list_system_log($type_id, $_GET[sortvalue], $_GET[sortmethod], 0, 20);
			}
			else
			{
				$result_array = SystemFE_Wrapper::list_system_log($type_id, null, null, 0, 20);
			}	
		}
		
		if (is_array($result_array) and count($result_array) >= 1)
		{		
			foreach($result_array as $key => $value)
			{	
				$datetime_handler = new DatetimeHandler($result_array[$key][datetime]);
				$result_array[$key][datetime] = $datetime_handler->get_formatted_string("dS M Y H:i");

				if (strlen($result_array[$key][info]) > 20)
				{
					$result_array[$key][info] = substr($result_array[$key][info],0,20)."...";
				}
				
				if (strlen($result_array[$key][file]) > 20)
				{
					$result_array[$key][file] = substr($result_array[$key][file],0,20)."...";
				}
				
				$paramquery = $_GET;
				$paramquery[id] = $result_array[$key][id];
				$paramquery[action] = "detail";
				unset($paramquery[sortvalue]);
				unset($paramquery[sortmethod]);
				unset($paramquery[nextpage]);
				$params = http_build_query($paramquery, '', '&#38;');

				$tmp_datetime = $result_array[$key][datetime];
				unset($result_array[$key][datetime]);
				$result_array[$key][datetime][link] = $params;
				$result_array[$key][datetime][content] = $tmp_datetime;
				
				
				$paramquery = $_GET;
				$paramquery[id] = $result_array[$key][ip];
				$paramquery[action] = "ip_info";
				unset($paramquery[sortvalue]);
				unset($paramquery[sortmethod]);
				unset($paramquery[nextpage]);
				$params = http_build_query($paramquery, '', '&#38;');

				$tmp_ip = $result_array[$key][ip];
				unset($result_array[$key][ip]);				
				$result_array[$key][ip][link] = $params;
				$result_array[$key][ip][content] = $tmp_ip;
			}
		}
		else
		{
			$list->override_last_line("<span class='italic'>No results found!</span>");
		}
		
		$template = new Template("template/base/admin/system_log/list.html");
										
		$result = array();
		$counter = 0;

		$type_array = SystemLog::list_types();

		if (is_array($type_array) and count($type_array) >= 1)
		{
			foreach($type_array as $key => $value)
			{
				if ($type_id == $value)
				{
					$result[$counter][selected] = "selected='selected'";
				}
				else
				{
					$result[$counter][selected] = "";
				}
				
				$result[$counter][value] = $value;
				$result[$counter][content] = SystemLog::get_type_name($value);
				$counter++;
			}
		}
		
		$template->set_var("option",$result);
		
		
		$result = array();
		$counter = 0;
		
		foreach($_GET as $key => $value)
		{
			if ($key != "runid")
			{
				$result[$counter][value] = $value;
				$result[$counter][key] = $key;
				$counter++;
			}
		}
		
		$template->set_var("get",$result);
		
		$template->set_var("table", $list->get_list($result_array, $_GET[page]));			
		
		$template->output();
	}

	/**
	 * @throws SystemLogIDMissingException
	 */
	public static function detail()
	{
		if ($_GET[id])
		{
			
			$system_log = new SystemLog($_GET[id]);
			$user = new User($system_log->get_user_id());
			$datetime_handler = new DatetimeHandler($system_log->get_datetime());
		
			$template = new Template("template/base/admin/system_log/detail.html");
			
			$template->set_var("datetime", $datetime_handler->get_formatted_string("dS M Y H:i"));

			if ($system_log->get_user_id())
			{
				$template->set_var("username", $user->get_username()." (".$user->get_full_name(false).")");
			}
			else
			{
				$template->set_var("username", "");
			}

			if ($system_log->get_ip())
			{
				$template->set_var("ip", $system_log->get_ip());
			}
			else
			{
				$template->set_var("ip", "");
			}
			
			if ($system_log->get_file())
			{
				$template->set_var("file", $system_log->get_file());
			}
			else
			{
				$template->set_var("file", "");
			}
			
			if ($system_log->get_line())
			{
				$template->set_var("line", $system_log->get_line());
			}
			else
			{
				$template->set_var("line", "");
			}
			
			if ($system_log->get_content_int())
			{
				$template->set_var("content_int", $system_log->get_content_int());
			}
			else
			{
				$template->set_var("content_int", "");
			}
			
			if ($system_log->get_content_string())
			{
				$template->set_var("content_string", $system_log->get_content_string());
			}
			else
			{
				$template->set_var("content_string", "");
			}
			
			if ($system_log->get_link())
			{
				$unserialized_string = unserialize($system_log->get_link());
				
				if (is_array($unserialized_string))
				{
					if (count($unserialized_string) >= 1)
					{
						$string = "";
						
						foreach($unserialized_string as $key => $value)
						{
							$string .= $key." => ".$value."<br />";
						}
						
						$template->set_var("get", $string);
					}
					else
					{
						$template->set_var("get", "");
					}
				}
				else
				{
					$template->set_var("get", $system_log->get_link());
				}
			}
			else
			{
				$template->set_var("get", "");
			}
			$template->output();
		}
		else
		{
			throw new SystemLogIDMissingException();
		}
	}
	
	/**
	 * @throws SystemLogIDMissingException
	 */
	public static function ip_info()
	{
		if ($_GET[id])
		{
			$ip = $_GET[id];
			$successful_logins = SystemLog::count_ip_successful_logins($ip);
			$failed_logins = SystemLog::count_ip_failed_logins($ip);
		
			$template = new Template("template/base/admin/system_log/ip_info.html");
			
			$template->set_var("ip", $ip);
			
			if ($successful_logins)
			{
				$template->set_var("successful_logins", $successful_logins);
			}
			else
			{
				$template->set_var("successful_logins", 0);
			}
			
			if ($failed_logins)
			{
				$template->set_var("failed_logins", $failed_logins);
			}
			else
			{
				$template->set_var("failed_logins", 0);
			}
			
			$user_array = SystemLog::list_ip_users($ip);
			$user_content_array = array();
			
			$counter = 0;
			
			if (is_array($user_array) and count($user_array) >= 1)
			{
				foreach($user_array as $key => $value)
				{
					$user = new User($value);
					
					$user_content_array[$counter][username] = $user->get_username();
					$user_content_array[$counter][fullname] = $user->get_full_name(false);
					
					$counter++;
				}
				$template->set_var("no_user", false);
			}
			else
			{
				$template->set_var("no_user", true);
			}
			
			$template->set_var("user", $user_content_array);
			
			$template->output();
		}
		else
		{
			throw new SystemLogIDMissingException();
		}
	}
		
	public static function handler()
	{		
		switch($_GET[action]):
				
			case "detail":
				self::detail();
			break;
			
			case "ip_info":
				self::ip_info();
			break;	
						
			default:
				self::home();
			break;

		endswitch;
	}
	
}

?>
