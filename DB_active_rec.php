<?php  die('This file is not really here!');

/**
 * ------------- DO NOT UPLOAD THIS FILE TO LIVE SERVER ---------------------
 * -------------------------------------------------------------------------------
 * phpStorm Helper file for Active Record MySQL code completion
 * -------------------------------------------------------------------------------
 * Right click on system/core/database/DB_active_rec.php and Mark As Plain Text
 * -------------------------------------------------------------------------------
 *
 * PHP version 5
 *
 * LICENSE: GPL http://www.gnu.org/copyleft/gpl.html
 *
 * Created 2/03/12, 2:06 PM
 *
 * @category
 * @package    CodeIgniter Active Record DB_active_rec.php
 * @author     Jeff Behnke
 * @copyright  2009-11 Valid-Webs.com
 * @license    GPL http://www.gnu.org/copyleft/gpl.html
 * @version    2012.02.03
 */


/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author         ExpressionEngine Dev Team
 * @copyright      Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link           http://codeigniter.com
 * @since          Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Active Record Class
 *
 * This is the platform-independent base Active Record implementation class.
 *
 * @package        CodeIgniter
 * @subpackage     Drivers
 * @category       Database
 * @author         ExpressionEngine Dev Team
 * @link           http://codeigniter.com/user_guide/database/
 *
 * @method      int insert_id() insert_id() The insert ID number when performing database inserts.
 * @method      int affected_rows() affected_rows() The number of affected rows, when doing "write" type queries (insert, update, etc.).
 * @method      int count_all() count_all(string $table) Determine the number of rows in a particular table.
 */
class CI_DB_active_record extends CI_DB_driver
{

	var $ar_select = array();

	var $ar_distinct = FALSE;

	var $ar_from = array();

	var $ar_join = array();

	var $ar_where = array();

	var $ar_like = array();

	var $ar_groupby = array();

	var $ar_having = array();

	var $ar_keys = array();

	var $ar_limit = FALSE;

	var $ar_offset = FALSE;

	var $ar_order = FALSE;

	var $ar_orderby = array();

	var $ar_set = array();

	var $ar_wherein = array();

	var $ar_aliased_tables = array();

	var $ar_store_array = array();

	// Active Record Caching variables
	var $ar_caching = FALSE;

	var $ar_cache_exists = array();

	var $ar_cache_select = array();

	var $ar_cache_from = array();

	var $ar_cache_join = array();

	var $ar_cache_where = array();

	var $ar_cache_like = array();

	var $ar_cache_groupby = array();

	var $ar_cache_having = array();

	var $ar_cache_orderby = array();

	var $ar_cache_set = array();

	var $ar_no_escape = array();

	var $ar_cache_no_escape = array();

	// --------------------------------------------------------------------

	/**
	 * Select
	 *
	 * Generates the SELECT portion of the query
	 *
	 * @param string $select
	 * @param null   $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function select($select = '*', $escape = NULL)
	{
		if (is_string($select))
		{
			$select = explode(',', $select);
		}

		foreach ($select as $val)
		{
			$val = trim($val);

			if ($val != '')
			{
				$this->ar_select[]    = $val;
				$this->ar_no_escape[] = $escape;

				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_select[]    = $val;
					$this->ar_cache_exists[]    = 'select';
					$this->ar_cache_no_escape[] = $escape;
				}
			}
		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Max
	 *
	 * Generates a SELECT MAX(field) portion of a query
	 *
	 * @param string $select the field
	 * @param string $alias  an alias
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function select_max($select = '', $alias = '')
	{
		$this->_max_min_avg_sum($select, $alias, 'MAX');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Min
	 *
	 * Generates a SELECT MIN(field) portion of a query
	 *
	 * @param string $select the field
	 * @param string $alias  an alias
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function select_min($select = '', $alias = '')
	{
		$this->_max_min_avg_sum($select, $alias, 'MIN');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Average
	 *
	 * Generates a SELECT AVG(field) portion of a query
	 *
	 * @param string $select the field
	 * @param string $alias  an alias
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function select_avg($select = '', $alias = '')
	{
		$this->_max_min_avg_sum($select, $alias, 'AVG');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Select Sum
	 *
	 * Generates a SELECT SUM(field) portion of a query
	 *
	 * @param string $select the field
	 * @param string $alias  an alias
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function select_sum($select = '', $alias = '')
	{
		return $this->_max_min_avg_sum($select, $alias, 'SUM');
	}

	// --------------------------------------------------------------------

	/**
	 * Processing Function for the four functions above:
	 *
	 *	select_max()
	 *	select_min()
	 *	select_avg()
	 *  select_sum()
	 *
	 * @param string $select the string field
	 * @param string $alias  an string alias
	 * @param string $type
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	protected function _max_min_avg_sum($select = '', $alias = '', $type = 'MAX')
	{
		if (!is_string($select) OR $select == '')
		{
			$this->display_error('db_invalid_query');
		}

		$type = strtoupper($type);

		if (!in_array($type, array(
			'MAX',
			'MIN',
			'AVG',
			'SUM'
		))
		)
		{
			show_error('Invalid function type: ' . $type);
		}

		if ($alias == '')
		{
			$alias = $this->_create_alias_from_table(trim($select));
		}

		$sql = $type . '(' . $this->_protect_identifiers(trim($select)) . ') AS ' . $alias;

		$this->ar_select[] = $sql;

		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_select[] = $sql;
			$this->ar_cache_exists[] = 'select';
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Determines the alias name based on the table
	 *
	 * @param string $item
	 *
	 * @return string
	 */
	protected function _create_alias_from_table($item)
	{
		if (strpos($item, '.') !== FALSE)
		{
			return end(explode('.', $item));
		}

		return $item;
	}

	// --------------------------------------------------------------------

	/**
	 * DISTINCT
	 *
	 * Sets a flag which tells the query string compiler to add DISTINCT
	 *
	 * @param bool $val
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function distinct($val = TRUE)
	{
		$this->ar_distinct = (is_bool($val)) ? $val : TRUE;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * From
	 *
	 * Generates the FROM portion of the query
	 *
	 * @param mixed $from can be a string or array
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function from($from)
	{
		foreach ((array) $from as $val)
		{
			if (strpos($val, ',') !== FALSE)
			{
				foreach (explode(',', $val) as $v)
				{
					$v = trim($v);
					$this->_track_aliases($v);

					$this->ar_from[] = $this->_protect_identifiers($v, TRUE, NULL, FALSE);

					if ($this->ar_caching === TRUE)
					{
						$this->ar_cache_from[]   = $this->_protect_identifiers($v, TRUE, NULL, FALSE);
						$this->ar_cache_exists[] = 'from';
					}
				}

			}
			else
			{
				$val = trim($val);

				// Extract any aliases that might exist.  We use this information
				// in the _protect_identifiers to know whether to add a table prefix
				$this->_track_aliases($val);

				$this->ar_from[] = $this->_protect_identifiers($val, TRUE, NULL, FALSE);

				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_from[]   = $this->_protect_identifiers($val, TRUE, NULL, FALSE);
					$this->ar_cache_exists[] = 'from';
				}
			}
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Join
	 *
	 * Generates the JOIN portion of the query
	 *
	 * @param string $table
	 * @param string $cond the join condition
	 * @param string $type the type of join
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function join($table, $cond, $type = '')
	{
		if ($type != '')
		{
			$type = strtoupper(trim($type));

			if (!in_array($type, array(
				'LEFT',
				'RIGHT',
				'OUTER',
				'INNER',
				'LEFT OUTER',
				'RIGHT OUTER'
			))
			)
			{
				$type = '';
			}
			else
			{
				$type .= ' ';
			}
		}

		// Extract any aliases that might exist.  We use this information
		// in the _protect_identifiers to know whether to add a table prefix
		$this->_track_aliases($table);

		// Strip apart the condition and protect the identifiers
		if (preg_match('/([\w\.]+)([\W\s]+)(.+)/', $cond, $match))
		{
			$match[1] = $this->_protect_identifiers($match[1]);
			$match[3] = $this->_protect_identifiers($match[3]);

			$cond = $match[1] . $match[2] . $match[3];
		}

		// Assemble the JOIN statement
		$join = $type . 'JOIN ' . $this->_protect_identifiers($table, TRUE, NULL, FALSE) . ' ON ' . $cond;

		$this->ar_join[] = $join;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_join[]   = $join;
			$this->ar_cache_exists[] = 'join';
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Where
	 *
	 * Generates the WHERE portion of the query. Separates
	 * multiple calls with AND
	 *
	 * @param mixed $key
	 * @param null  $value
	 * @param bool  $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'AND ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * OR Where
	 *
	 * Generates the WHERE portion of the query. Separates
	 * multiple calls with OR
	 *
	 * @param mixed $key
	 * @param null  $value
	 * @param bool  $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_where($key, $value = NULL, $escape = TRUE)
	{
		return $this->_where($key, $value, 'OR ', $escape);
	}

	// --------------------------------------------------------------------

	/**
	 * Where
	 *
	 * Called by where() or or_where()
	 *
	 * @param mixed  $key
	 * @param null   $value
	 * @param string $type
	 * @param null   $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	protected function _where($key, $value = NULL, $type = 'AND ', $escape = NULL)
	{
		if (!is_array($key))
		{
			$key = array($key => $value);
		}

		// If the escape value was not set will will base it on the global setting
		if (!is_bool($escape))
		{
			$escape = $this->_protect_identifiers;
		}

		foreach ($key as $k => $v)
		{
			$prefix = (count($this->ar_where) == 0 AND count($this->ar_cache_where) == 0) ? '' : $type;

			if (is_null($v) && !$this->_has_operator($k))
			{
				// value appears not to have been set, assign the test to IS NULL
				$k .= ' IS NULL';
			}

			if (!is_null($v))
			{
				if ($escape === TRUE)
				{
					$k = $this->_protect_identifiers($k, FALSE, $escape);

					$v = ' ' . $this->escape($v);
				}

				if (!$this->_has_operator($k))
				{
					$k .= ' = ';
				}
			}
			else
			{
				$k = $this->_protect_identifiers($k, FALSE, $escape);
			}

			$this->ar_where[] = $prefix . $k . $v;

			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_where[]  = $prefix . $k . $v;
				$this->ar_cache_exists[] = 'where';
			}

		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Where_in
	 *
	 * Generates a WHERE field IN ('item', 'item') SQL query joined with
	 * AND if appropriate
	 *
	 * @param string $key    The field to search
	 * @param array  $values The values searched on
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values);
	}

	// --------------------------------------------------------------------

	/**
	 * Where_in_or
	 *
	 * Generates a WHERE field IN ('item', 'item') SQL query joined with
	 * OR if appropriate
	 *
	 * @param string $key    The field to search
	 * @param array  $values The values searched on
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_where_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, FALSE, 'OR ');
	}

	// --------------------------------------------------------------------

	/**
	 * Where_not_in
	 *
	 * Generates a WHERE field NOT IN ('item', 'item') SQL query joined
	 * with AND if appropriate
	 *
	 * @param string $key    The field to search
	 * @param array  $values The values searched on
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE);
	}

	// --------------------------------------------------------------------

	/**
	 * Where_not_in_or
	 *
	 * Generates a WHERE field NOT IN ('item', 'item') SQL query joined
	 * with OR if appropriate
	 *
	 * @param string $key    The field to search
	 * @param array  $values The values searched on
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_where_not_in($key = NULL, $values = NULL)
	{
		return $this->_where_in($key, $values, TRUE, 'OR ');
	}

	// --------------------------------------------------------------------

	/**
	 * Where_in
	 *
	 * Called by where_in, where_in_or, where_not_in, where_not_in_or
	 *
	 * @param string  $key    The field to search
	 * @param array   $values The values searched on
	 * @param boolean $not    If the statement would be IN or NOT IN
	 * @param string  $type
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	protected function _where_in($key = NULL, $values = NULL, $not = FALSE, $type = 'AND ')
	{
		if ($key === NULL OR $values === NULL)
		{
			return null;
		}

		if (!is_array($values))
		{
			$values = array($values);
		}

		$not = ($not) ? ' NOT' : '';

		foreach ($values as $value)
		{
			$this->ar_wherein[] = $this->escape($value);
		}

		$prefix = (count($this->ar_where) == 0) ? '' : $type;

		$where_in = $prefix . $this->_protect_identifiers($key) . $not . " IN (" . implode(", ", $this->ar_wherein) . ") ";

		$this->ar_where[] = $where_in;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_where[]  = $where_in;
			$this->ar_cache_exists[] = 'where';
		}

		// reset the array for multiple calls
		$this->ar_wherein = array();
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Like
	 *
	 * Generates a %LIKE% portion of the query. Separates
	 * multiple calls with AND
	 *
	 * @param string $field
	 * @param string $match
	 * @param string $side
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function like($field, $match = '', $side = 'both')
	{
		$this->_like($field, $match, 'AND ', $side);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Not Like
	 *
	 * Generates a NOT LIKE portion of the query. Separates
	 * multiple calls with AND
	 *
	 * @param string $field
	 * @param string $match
	 * @param string $side
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function not_like($field, $match = '', $side = 'both')
	{
		$this->_like($field, $match, 'AND ', $side, 'NOT');

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * OR Like
	 *
	 * Generates a %LIKE% portion of the query. Separates
	 * multiple calls with OR
	 *
	 * @param string $field
	 * @param string $match
	 * @param string $side
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side);
	}

	// --------------------------------------------------------------------

	/**
	 * OR Not Like
	 *
	 * Generates a NOT LIKE portion of the query. Separates
	 * multiple calls with OR
	 *
	 * @param string $field
	 * @param mixed  $match
	 * @param string $side
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_not_like($field, $match = '', $side = 'both')
	{
		return $this->_like($field, $match, 'OR ', $side, 'NOT');
	}

	// --------------------------------------------------------------------

	/**
	 * Like
	 *
	 * Called by like() or orlike()
	 *
	 * @param string $field
	 * @param string $match
	 * @param string $type
	 * @param string $side
	 * @param string $not
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	protected function _like($field, $match = '', $type = 'AND ', $side = 'both', $not = '')
	{
		if (!is_array($field))
		{
			$field = array($field => $match);
		}

		foreach ($field as $k => $v)
		{
			$k = $this->_protect_identifiers($k);

			$prefix = (count($this->ar_like) == 0) ? '' : $type;

			$v = $this->escape_like_str($v);

			if ($side == 'before')
			{
				$like_statement = $prefix . " $k $not LIKE '%{$v}'";
			}
			elseif ($side == 'after')
			{
				$like_statement = $prefix . " $k $not LIKE '{$v}%'";
			}
			else
			{
				$like_statement = $prefix . " $k $not LIKE '%{$v}%'";
			}

			// some platforms require an escape sequence definition for LIKE wildcards
			if ($this->_like_escape_str != '')
			{
				$like_statement = $like_statement . sprintf($this->_like_escape_str, $this->_like_escape_chr);
			}

			$this->ar_like[] = $like_statement;
			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_like[]   = $like_statement;
				$this->ar_cache_exists[] = 'like';
			}

		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * GROUP BY
	 *
	 * @param string $by
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function group_by($by)
	{
		if (is_string($by))
		{
			$by = explode(',', $by);
		}

		foreach ($by as $val)
		{
			$val = trim($val);

			if ($val != '')
			{
				$this->ar_groupby[] = $this->_protect_identifiers($val);

				if ($this->ar_caching === TRUE)
				{
					$this->ar_cache_groupby[] = $this->_protect_identifiers($val);
					$this->ar_cache_exists[]  = 'groupby';
				}
			}
		}
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the HAVING value
	 *
	 * Separates multiple calls with AND
	 *
	 * @param mixed $key
	 * @param string $value
	 * @param bool   $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function having($key, $value = '', $escape = TRUE)
	{
		$this->_having($key, $value, 'AND ', $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the OR HAVING value
	 *
	 * Separates multiple calls with OR
	 *
	 * @param mixed  $key
	 * @param string $value
	 * @param bool   $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function or_having($key, $value = '', $escape = TRUE)
	{
		$this->_having($key, $value, 'OR ', $escape);

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the HAVING values
	 *
	 * Called by having() or or_having()
	 *
	 * @param mixed $key
	 * @param string $value
	 * @param string $type
	 * @param bool   $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	protected function _having($key, $value = '', $type = 'AND ', $escape = TRUE)
	{
		if (!is_array($key))
		{
			$key = array($key => $value);
		}

		foreach ($key as $k => $v)
		{
			$prefix = (count($this->ar_having) == 0) ? '' : $type;

			if ($escape === TRUE)
			{
				$k = $this->_protect_identifiers($k);
			}

			if (!$this->_has_operator($k))
			{
				$k .= ' = ';
			}

			if ($v != '')
			{
				$v = ' ' . $this->escape($v);
			}

			$this->ar_having[] = $prefix . $k . $v;
			if ($this->ar_caching === TRUE)
			{
				$this->ar_cache_having[] = $prefix . $k . $v;
				$this->ar_cache_exists[] = 'having';
			}
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the ORDER BY value
	 *
	 * @param string $orderby
	 * @param string $direction: asc or desc
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function order_by($orderby, $direction = '')
	{
		if (strtolower($direction) == 'random')
		{
			$orderby   = ''; // Random results want or don't need a field name
			$direction = $this->_random_keyword;
		}
		elseif (trim($direction) != '')
		{
			$direction = (in_array(strtoupper(trim($direction)), array(
				'ASC',
				'DESC'
			), TRUE)) ? ' ' . $direction : ' ASC';
		}

		if (strpos($orderby, ',') !== FALSE)
		{
			$temp = array();
			foreach (explode(',', $orderby) as $part)
			{
				$part = trim($part);
				if (!in_array($part, $this->ar_aliased_tables))
				{
					$part = $this->_protect_identifiers(trim($part));
				}

				$temp[] = $part;
			}

			$orderby = implode(', ', $temp);
		}
		else if ($direction != $this->_random_keyword)
		{
			$orderby = $this->_protect_identifiers($orderby);
		}

		$orderby_statement = $orderby . $direction;

		$this->ar_orderby[] = $orderby_statement;
		if ($this->ar_caching === TRUE)
		{
			$this->ar_cache_orderby[] = $orderby_statement;
			$this->ar_cache_exists[]  = 'orderby';
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the LIMIT value
	 *
	 * @param integer $value the offset value
	 * @param string  $offset
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function limit($value, $offset = '')
	{
		$this->ar_limit = (int) $value;

		if ($offset != '')
		{
			$this->ar_offset = (int) $offset;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Sets the OFFSET value
	 *
	 * @param integer $offset the offset value
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function offset($offset)
	{
		$this->ar_offset = $offset;
		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * The "set" function.  Allows key/value pairs to be set for inserting or updating
	 *
	 * @param mixed   $key
	 * @param string  $value
	 * @param boolean $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function set($key, $value = '', $escape = TRUE)
	{
		$key = $this->_object_to_array($key);

		if (!is_array($key))
		{
			$key = array($key => $value);
		}

		foreach ($key as $k => $v)
		{
			if ($escape === FALSE)
			{
				$this->ar_set[$this->_protect_identifiers($k)] = $v;
			}
			else
			{
				$this->ar_set[$this->_protect_identifiers($k, FALSE, TRUE)] = $this->escape($v);
			}
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get
	 *
	 * Compiles the select statement based on the other functions called
	 * and runs the query
	 *
	 * @param string $table  the table
	 * @param string $limit  the limit clause
	 * @param string $offset the offset clause
	 *
	 * @return mixed $result
	 */
	public function get($table = '', $limit = null, $offset = null)
	{
		if ($table != '')
		{
			$this->_track_aliases($table);
			$this->from($table);
		}

		if (!is_null($limit))
		{
			$this->limit($limit, $offset);
		}

		$sql = $this->_compile_select();

		$result = $this->query($sql);
		$this->_reset_select();

		return $result;
	}

	/**
	 * "Count All Results" query
	 *
	 * Generates a platform-specific query string that counts all records
	 * returned by an Active Record query.
	 *
	 * @param string $table
	 *
	 * @return int
	 */
	public function count_all_results($table = '')
	{
		if ($table != '')
		{
			$this->_track_aliases($table);
			$this->from($table);
		}

		$sql = $this->_compile_select($this->_count_string . $this->_protect_identifiers('numrows'));

		$query = $this->query($sql);
		$this->_reset_select();

		if ($query->num_rows() == 0)
		{
			return 0;
		}

		$row = $query->row();
		return (int) $row->numrows;
	}

	// --------------------------------------------------------------------

	/**
	 * Get_Where
	 *
	 * Allows the where clause, limit and offset to be added directly
	 *
	 * @param string $table
	 * @param null   $where  the string where clause
	 * @param null   $limit  the string limit clause
	 * @param null   $offset the string offset clause
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function get_where($table = '', $where = null, $limit = null, $offset = null)
	{
		if ($table != '')
		{
			$this->from($table);
		}

		if (!is_null($where))
		{
			$this->where($where);
		}

		if (!is_null($limit))
		{
			$this->limit($limit, $offset);
		}

		$sql = $this->_compile_select();

		$result = $this->query($sql);
		$this->_reset_select();
		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert_Batch
	 *
	 * Compiles batch insert strings and runs the queries
	 *
	 * @param string $table the table to retrieve the results from
	 * @param array  $set   an associative array of insert values
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function insert_batch($table = '', $set = NULL)
	{
		if (!is_null($set))
		{
			$this->set_insert_batch($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				//No valid data array.  Folds in cases where keys and values did not match up
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		// Batch this baby
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{

			$sql = $this->_insert_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_keys, array_slice($this->ar_set, $i, 100));

			//echo $sql;

			$this->query($sql);
		}

		$this->_reset_write();

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * The "set_insert_batch" function.  Allows key/value pairs to be set for batch inserts
	 *
	 * @param mixed   $key
	 * @param string  $value
	 * @param boolean $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function set_insert_batch($key, $value = '', $escape = TRUE)
	{
		$key = $this->_object_to_array_batch($key);

		if (!is_array($key))
		{
			$key = array($key => $value);
		}

		$keys = array_keys(current($key));
		sort($keys);

		foreach ($key as $row)
		{
			if (count(array_diff($keys, array_keys($row))) > 0 OR count(array_diff(array_keys($row), $keys)) > 0)
			{
				// batch function above returns an error on an empty array
				$this->ar_set[] = array();
				return;
			}

			ksort($row); // puts $row in the same order as our keys

			if ($escape === FALSE)
			{
				$this->ar_set[] = '(' . implode(',', $row) . ')';
			}
			else
			{
				$clean = array();

				foreach ($row as $value)
				{
					$clean[] = $this->escape($value);
				}

				$this->ar_set[] = '(' . implode(',', $clean) . ')';
			}
		}

		foreach ($keys as $k)
		{
			$this->ar_keys[] = $this->_protect_identifiers($k);
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Insert
	 *
	 * Compiles an insert string and runs the query
	 *
	 * @param string $table the table to insert data into
	 * @param array  $set   an associative array of insert values
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	function insert($table = '', $set = NULL)
	{
		if (!is_null($set))
		{
			$this->set($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		$sql = $this->_insert($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));
		dump($sql);
		$this->_reset_write();
		return $this->query($sql);
	}

	// --------------------------------------------------------------------

	/**
	 * Replace
	 *
	 * Compiles an replace into string and runs the query
	 *
	 * @param string $table the table to replace data into
	 * @param array  $set   an associative array of insert values
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function replace($table = '', $set = NULL)
	{
		if (!is_null($set))
		{
			$this->set($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		$sql = $this->_replace($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_keys($this->ar_set), array_values($this->ar_set));

		$this->_reset_write();
		return $this->query($sql);
	}

	// --------------------------------------------------------------------

	/**
	 * Update
	 *
	 * Compiles an update string and runs the query
	 *
	 * @param string   $table    the table to retrieve the results from
	 * @param array    $set      an associative array of update values
	 * @param mixed    $where    the where clause
	 * @param bool     $limit
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
	{
		// Combine any cached components with the current statements
		$this->_merge_cache();

		if (!is_null($set))
		{
			$this->set($set);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}
			return FALSE;
		}

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		if ($where != NULL)
		{
			$this->where($where);
		}

		if ($limit != NULL)
		{
			$this->limit($limit);
		}

		$sql = $this->_update($this->_protect_identifiers($table, TRUE, NULL, FALSE), $this->ar_set, $this->ar_where, $this->ar_orderby, $this->ar_limit);

		$this->_reset_write();
		return $this->query($sql);
	}


	// --------------------------------------------------------------------

	/**
	 * Update_Batch
	 *
	 * Compiles an update string and runs the query
	 *
	 * @param string $table the table to retrieve the results from
	 * @param array  $set   an associative array of update values
	 * @param string $index the where key
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function update_batch($table = '', $set = NULL, $index = NULL)
	{
		// Combine any cached components with the current statements
		$this->_merge_cache();

		if (is_null($index))
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_index');
			}

			return FALSE;
		}

		if (!is_null($set))
		{
			$this->set_update_batch($set, $index);
		}

		if (count($this->ar_set) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_must_use_set');
			}

			return FALSE;
		}

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}

		// Batch this baby
		for ($i = 0, $total = count($this->ar_set); $i < $total; $i = $i + 100)
		{
			$sql = $this->_update_batch($this->_protect_identifiers($table, TRUE, NULL, FALSE), array_slice($this->ar_set, $i, 100), $this->_protect_identifiers($index), $this->ar_where);

			$this->query($sql);
		}

		$this->_reset_write();
	}

	// --------------------------------------------------------------------

	/**
	 * The "set_update_batch" function.  Allows key/value pairs to be set for batch updating
	 *
	 * @param array   $key
	 * @param string  $index
	 * @param boolean $escape
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function set_update_batch($key, $index = '', $escape = TRUE)
	{
		$key = $this->_object_to_array_batch($key);

		if (!is_array($key))
		{
			// @todo error
		}

		foreach ($key as $k => $v)
		{
			$index_set = FALSE;
			$clean     = array();

			foreach ($v as $k2 => $v2)
			{
				if ($k2 == $index)
				{
					$index_set = TRUE;
				}
				else
				{
					$not[] = $k . '-' . $v;
				}

				if ($escape === FALSE)
				{
					$clean[$this->_protect_identifiers($k2)] = $v2;
				}
				else
				{
					$clean[$this->_protect_identifiers($k2)] = $this->escape($v2);
				}
			}

			if ($index_set == FALSE)
			{
				return $this->display_error('db_batch_missing_index');
			}

			$this->ar_set[] = $clean;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Empty Table
	 *
	 * Compiles a delete string and runs "DELETE FROM table"
	 *
	 * @param string $table the table to empty
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function empty_table($table = '')
	{
		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}

		$sql = $this->_delete($table);

		$this->_reset_write();

		return $this->query($sql);
	}

	// --------------------------------------------------------------------

	/**
	 * Truncate
	 *
	 * Compiles a truncate string and runs the query
	 * If the database does not support the truncate() command
	 * This function maps to "DELETE FROM table"
	 *
	 * @param string $table the table to truncate
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function truncate($table = '')
	{
		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}

		$sql = $this->_truncate($table);

		$this->_reset_write();

		return $this->query($sql);
	}

	// --------------------------------------------------------------------

	/**
	 * Delete
	 *
	 * Compiles a delete string and runs the query
	 *
	 * @param mixed   $table the table(s) to delete from. String or array
	 * @param mixed   $where the where clause
	 * @param mixed   $limit the limit clause
	 * @param boolean $reset_data
	 *
	 * @return CI_DB_active_record|CI_DB_result
	 */
	public function delete($table = '', $where = '', $limit = NULL, $reset_data = TRUE)
	{
		// Combine any cached components with the current statements
		$this->_merge_cache();

		if ($table == '')
		{
			if (!isset($this->ar_from[0]))
			{
				if ($this->db_debug)
				{
					return $this->display_error('db_must_set_table');
				}
				return FALSE;
			}

			$table = $this->ar_from[0];
		}
		elseif (is_array($table))
		{
			foreach ($table as $single_table)
			{
				$this->delete($single_table, $where, $limit, FALSE);
			}

			$this->_reset_write();
			return;
		}
		else
		{
			$table = $this->_protect_identifiers($table, TRUE, NULL, FALSE);
		}

		if ($where != '')
		{
			$this->where($where);
		}

		if ($limit != NULL)
		{
			$this->limit($limit);
		}

		if (count($this->ar_where) == 0 && count($this->ar_wherein) == 0 && count($this->ar_like) == 0)
		{
			if ($this->db_debug)
			{
				return $this->display_error('db_del_must_use_where');
			}

			return FALSE;
		}

		$sql = $this->_delete($table, $this->ar_where, $this->ar_like, $this->ar_limit);

		if ($reset_data)
		{
			$this->_reset_write();
		}

		return $this->query($sql);
	}

	// --------------------------------------------------------------------

	/**
	 * DB Prefix
	 *
	 * Prepends a database prefix if one exists in configuration
	 *
	 * @param string $table the table
	 *
	 * @return	string
	 */
	public function dbprefix($table = '')
	{
		if ($table == '')
		{
			$this->display_error('db_table_name_required');
		}

		return $this->dbprefix . $table;
	}

	// --------------------------------------------------------------------

	/**
	 * Set DB Prefix
	 *
	 * Set's the DB Prefix to something new without needing to reconnect
	 *
	 * @param string $prefix the prefix
	 *
	 * @return	string
	 */
	public function set_dbprefix($prefix = '')
	{
		return $this->dbprefix = $prefix;
	}

	// --------------------------------------------------------------------

	/**
	 * Track Aliases
	 *
	 * Used to track SQL statements written with aliased tables.
	 *
	 * @param string $table The table to inspect
	 *
	 * @return	string
	 */
	protected function _track_aliases($table)
	{
		if (is_array($table))
		{
			foreach ($table as $t)
			{
				$this->_track_aliases($t);
			}
			return;
		}

		// Does the string contain a comma?  If so, we need to separate
		// the string into discreet statements
		if (strpos($table, ',') !== FALSE)
		{
			return $this->_track_aliases(explode(',', $table));
		}

		// if a table alias is used we can recognize it by a space
		if (strpos($table, " ") !== FALSE)
		{
			// if the alias is written with the AS keyword, remove it
			$table = preg_replace('/ AS /i', ' ', $table);

			// Grab the alias
			$table = trim(strrchr($table, " "));

			// Store the alias, if it doesn't already exist
			if (!in_array($table, $this->ar_aliased_tables))
			{
				$this->ar_aliased_tables[] = $table;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Compile the SELECT statement
	 *
	 * Generates a query string based on which functions were used.
	 * Should not be called directly.  The get() function calls it.
	 *
	 * @param bool $select_override
	 *
	 * @return string
	 */
	protected function _compile_select($select_override = FALSE)
	{
		// Combine any cached components with the current statements
		$this->_merge_cache();

		// ----------------------------------------------------------------

		// Write the "select" portion of the query

		if ($select_override !== FALSE)
		{
			$sql = $select_override;
		}
		else
		{
			$sql = (!$this->ar_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

			if (count($this->ar_select) == 0)
			{
				$sql .= '*';
			}
			else
			{
				// Cycle through the "select" portion of the query and prep each column name.
				// The reason we protect identifiers here rather then in the select() function
				// is because until the user calls the from() function we don't know if there are aliases
				foreach ($this->ar_select as $key => $val)
				{
					$no_escape             = isset($this->ar_no_escape[$key]) ? $this->ar_no_escape[$key] : NULL;
					$this->ar_select[$key] = $this->_protect_identifiers($val, FALSE, $no_escape);
				}

				$sql .= implode(', ', $this->ar_select);
			}
		}

		// ----------------------------------------------------------------

		// Write the "FROM" portion of the query

		if (count($this->ar_from) > 0)
		{
			$sql .= "\nFROM ";

			$sql .= $this->_from_tables($this->ar_from);
		}

		// ----------------------------------------------------------------

		// Write the "JOIN" portion of the query

		if (count($this->ar_join) > 0)
		{
			$sql .= "\n";

			$sql .= implode("\n", $this->ar_join);
		}

		// ----------------------------------------------------------------

		// Write the "WHERE" portion of the query

		if (count($this->ar_where) > 0 OR count($this->ar_like) > 0)
		{
			$sql .= "\nWHERE ";
		}

		$sql .= implode("\n", $this->ar_where);

		// ----------------------------------------------------------------

		// Write the "LIKE" portion of the query

		if (count($this->ar_like) > 0)
		{
			if (count($this->ar_where) > 0)
			{
				$sql .= "\nAND ";
			}

			$sql .= implode("\n", $this->ar_like);
		}

		// ----------------------------------------------------------------

		// Write the "GROUP BY" portion of the query

		if (count($this->ar_groupby) > 0)
		{
			$sql .= "\nGROUP BY ";

			$sql .= implode(', ', $this->ar_groupby);
		}

		// ----------------------------------------------------------------

		// Write the "HAVING" portion of the query

		if (count($this->ar_having) > 0)
		{
			$sql .= "\nHAVING ";
			$sql .= implode("\n", $this->ar_having);
		}

		// ----------------------------------------------------------------

		// Write the "ORDER BY" portion of the query

		if (count($this->ar_orderby) > 0)
		{
			$sql .= "\nORDER BY ";
			$sql .= implode(', ', $this->ar_orderby);

			if ($this->ar_order !== FALSE)
			{
				$sql .= ($this->ar_order == 'desc') ? ' DESC' : ' ASC';
			}
		}

		// ----------------------------------------------------------------

		// Write the "LIMIT" portion of the query

		if (is_numeric($this->ar_limit))
		{
			$sql .= "\n";
			$sql = $this->_limit($sql, $this->ar_limit, $this->ar_offset);
		}

		return $sql;
	}

	// --------------------------------------------------------------------

	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/vals
	 *
	 * @param object $object
	 *
	 * @return array
	 */
	public function _object_to_array($object)
	{
		if (!is_object($object))
		{
			return $object;
		}

		$array = array();
		foreach (get_object_vars($object) as $key => $val)
		{
			// There are some built in keys we need to ignore for this conversion
			if (!is_object($val) && !is_array($val) && $key != '_parent_name')
			{
				$array[$key] = $val;
			}
		}

		return $array;
	}

	// --------------------------------------------------------------------

	/**
	 * Object to Array
	 *
	 * Takes an object as input and converts the class variables to array key/vals
	 *
	 * @param object $object
	 *
	 * @return array
	 */
	public function _object_to_array_batch($object)
	{
		if (!is_object($object))
		{
			return $object;
		}

		$array  = array();
		$out    = get_object_vars($object);
		$fields = array_keys($out);

		foreach ($fields as $val)
		{
			// There are some built in keys we need to ignore for this conversion
			if ($val != '_parent_name')
			{

				$i = 0;
				foreach ($out[$val] as $data)
				{
					$array[$i][$val] = $data;
					$i++;
				}
			}
		}

		return $array;
	}

	// --------------------------------------------------------------------

	/**
	 * Start Cache
	 *
	 * Starts AR caching
	 *
	 * @return void
	 */
	public function start_cache()
	{
		$this->ar_caching = TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Stop Cache
	 *
	 * Stops AR caching
	 *
	 * @return void
	 */
	public function stop_cache()
	{
		$this->ar_caching = FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Flush Cache
	 *
	 * Empties the AR cache
	 *
	 * @access public
	 * @return void
	 */
	public function flush_cache()
	{
		$this->_reset_run(array(
			'ar_cache_select'          => array(),
			'ar_cache_from'			=> array(),
			'ar_cache_join'			=> array(),
			'ar_cache_where'           => array(),
			'ar_cache_like'			=> array(),
			'ar_cache_groupby'         => array(),
			'ar_cache_having'          => array(),
			'ar_cache_orderby'         => array(),
			'ar_cache_set'             => array(),
			'ar_cache_exists'          => array(),
			'ar_cache_no_escape'       => array()
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Merge Cache
	 *
	 * When called, this function merges any cached AR arrays with
	 * locally called ones.
	 *
	 * @return void
	 */
	protected function _merge_cache()
	{
		if (count($this->ar_cache_exists) == 0)
		{
			return;
		}

		foreach ($this->ar_cache_exists as $val)
		{
			$ar_variable  = 'ar_' . $val;
			$ar_cache_var = 'ar_cache_' . $val;

			if (count($this->$ar_cache_var) == 0)
			{
				continue;
			}

			$this->$ar_variable = array_unique(array_merge($this->$ar_cache_var, $this->$ar_variable));
		}

		// If we are "protecting identifiers" we need to examine the "from"
		// portion of the query to determine if there are any aliases
		if ($this->_protect_identifiers === TRUE AND count($this->ar_cache_from) > 0)
		{
			$this->_track_aliases($this->ar_from);
		}

		$this->ar_no_escape = $this->ar_cache_no_escape;
	}

	// --------------------------------------------------------------------

	/**
	 * Resets the active record values.  Called by the get() function
	 *
	 * @param array $ar_reset_items An array of fields to reset
	 *
	 * @return void
	 */
	protected function _reset_run($ar_reset_items)
	{
		foreach ($ar_reset_items as $item => $default_value)
		{
			if (!in_array($item, $this->ar_store_array))
			{
				$this->$item = $default_value;
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Resets the active record values.  Called by the get() function
	 *
	 * @return void
	 */
	protected function _reset_select()
	{
		$ar_reset_items = array(
			'ar_select'			=> array(),
			'ar_from'              => array(),
			'ar_join'              => array(),
			'ar_where'             => array(),
			'ar_like'              => array(),
			'ar_groupby'           => array(),
			'ar_having'			=> array(),
			'ar_orderby'           => array(),
			'ar_wherein'           => array(),
			'ar_aliased_tables'	=> array(),
			'ar_no_escape'         => array(),
			'ar_distinct'          => FALSE,
			'ar_limit'             => FALSE,
			'ar_offset'			=> FALSE,
			'ar_order'             => FALSE,
		);

		$this->_reset_run($ar_reset_items);
	}

	// --------------------------------------------------------------------

	/**
	 * Resets the active record "write" values.
	 *
	 * Called by the insert() update() insert_batch() update_batch() and delete() functions
	 *
	 * @return void
	 */
	protected function _reset_write()
	{
		$ar_reset_items = array(
			'ar_set'          => array(),
			'ar_from'         => array(),
			'ar_where'		=> array(),
			'ar_like'         => array(),
			'ar_orderby'      => array(),
			'ar_keys'         => array(),
			'ar_limit'		=> FALSE,
			'ar_order'		=> FALSE
		);

		$this->_reset_run($ar_reset_items);
	}
}

/* End of file DB_active_rec.php */
/* Location: ./system/database/DB_active_rec.php */