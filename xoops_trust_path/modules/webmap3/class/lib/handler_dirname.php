<?php
// $Id: handler_dirname.php,v 1.1 2012/03/17 09:28:15 ohwada Exp $

//=========================================================
// webmap3 module
// 2009-02-11 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webmap3_lib_handler_dirname
//=========================================================
class webmap3_lib_handler_dirname extends webmap3_lib_handler_basic
{
	var $_DIRNAME;

	var $_table;
	var $_id_name;
	var $_pid_name;
	var $_title_name;

	var $_id           = 0;
	var $_xoops_groups = null;

	var $_use_prefix  = false;
	var $_NONE_VALUE  = '---' ;
	var $_PREFIX_NAME = 'prefix' ;
	var $_PREFIX_MARK = '.' ;
	var $_PREFIX_BAR  = '--' ;

	var $_PERM_ALLOW_ALL = '*' ;
	var $_PERM_DENOY_ALL = 'x' ;
	var $_PERM_SEPARATOR = '&' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webmap3_lib_handler_dirname( $dirname )
{
	$this->_DIRNAME = $dirname;

	$this->webmap3_lib_handler_basic();

	$this->_xoops_groups = $this->get_xoops_groups() ;
}

function set_table_prefix_dirname( $name )
{
	$this->set_table( $this->prefix_dirname( $name ) );
}

function set_table_prefix( $name )
{
	$this->set_table( $this->db_prefix( $name ) );
}

function set_table( $val )
{
	$this->_table = $val;
}

function get_table()
{
	return $this->_table;
}

function set_id_name( $val )
{
	$this->_id_name = $val;
}

function get_id_name()
{
	return $this->_id_name;
}

function set_pid_name( $val )
{
	$this->_pid_name = $val;
}

function get_pid_name()
{
	return $this->_pid_name;
}

function set_title_name( $val )
{
	$this->_title_name = $val;
}

function get_title_name()
{
	return $this->_title_name;
}

function get_id()
{
	return $this->_id;
}

function prefix_dirname( $name )
{
	return $this->db_prefix( $this->_DIRNAME .'_'. $name );
}

function set_use_prefix( $val )
{
	$this->_use_prefix = (bool)$val;
}

//---------------------------------------------------------
// insert
//---------------------------------------------------------
function insert( $row )
{
	// dummy
}

//---------------------------------------------------------
// update
//---------------------------------------------------------
function update( $row )
{
	// dummy
}

//---------------------------------------------------------
// delete
//---------------------------------------------------------
function delete( $row, $force=false )
{
	return $this->delete_by_id( $this->get_id_from_row( $row ), $force );
}

function delete_by_id( $id, $force=false )
{
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE '. $this->_id_name .'='. intval($id);
	return $this->query( $sql, 0, 0, $force );
}

function delete_by_id_array( $id_array )
{
	if ( !is_array($id_array) || !count($id_array) ) {
		return true;	// no action
	}

	$in   = implode( ',', $id_array );
	$sql  = 'DELETE FROM '. $this->_table;
	$sql .= ' WHERE '. $this->_id_name .' IN ('. $in .')';
	return $this->query( $sql );
}

function get_id_from_row( $row )
{
	if ( isset( $row[ $this->_id_name ] ) ) {
		$this->_id = $row[ $this->_id_name ];
		return $this->_id;
	}
	return null;
}

function truncate_table()
{
	$sql = 'TRUNCATE TABLE '.$this->_table;
	return $this->query( $sql );
}

//---------------------------------------------------------
// count
//---------------------------------------------------------
function exists_record()
{
	if ( $this->get_count_all() > 0 ) {
		return true;
	}
	return false;
}

function get_count_all()
{
	$sql  = 'SELECT COUNT(*) FROM '.$this->_table;
	return $this->get_count_by_sql( $sql );
}

function get_count_by_where( $where )
{
	$sql = 'SELECT COUNT(*) FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	return $this->get_count_by_sql( $sql );
}

//---------------------------------------------------------
// row
//---------------------------------------------------------
function get_row_by_id( $id )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $this->_id_name .'='. intval($id);
	return $this->get_row_by_sql( $sql );
}

function get_row_by_id_or_default( $id )
{
	$row = $this->get_row_by_id( $id );
	if ( ! is_array($row) ) {
		$row = $this->create();
	}
	return $row;
}

function create()
{
	// dummy
}

//---------------------------------------------------------
// rows
//---------------------------------------------------------
function get_rows_all_asc( $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_rows_all_desc( $limit=0, $offset=0, $key=null )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $this->_id_name .' DESC';
	return $this->get_rows_by_sql( $sql, $limit, $offset, $key );
}

function get_rows_by_where( $where, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_orderby( $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

function get_rows_by_groupby_orderby( $groupby, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT * FROM '. $this->_table;
	$sql .= ' GROUP BY '.$groupby;
	$sql .= ' ORDER BY '.$orderby;
	return $this->get_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// id array
//---------------------------------------------------------
function get_id_array_by_where( $where, $limit=0, $offset=0 )
{
	$sql  = 'SELECT '. $this->_id_name .' FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $this->_id_name .' ASC';
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

function get_id_array_by_where_orderby( $where, $orderby, $limit=0, $offset=0 )
{
	$sql  = 'SELECT '. $this->_id_name .' FROM '.$this->_table;
	$sql .= ' WHERE '. $where;
	$sql .= ' ORDER BY '. $orderby;
	return $this->get_first_rows_by_sql( $sql, $limit, $offset );
}

//---------------------------------------------------------
// search
//---------------------------------------------------------
function build_where_by_keyword_array( $keyword_array, $name, $andor='AND' )
{
	if ( !is_array($keyword_array) || !count($keyword_array) ) {
		return null;
	}

	switch ( strtolower($andor) )
	{
		case 'exact':
			$where = $this->build_where_keyword_single( $keyword_array[0], $name );
			return $where;

		case 'or':
			$andor_glue = 'OR';
			break;

		case 'and':
		default:
			$andor_glue = 'AND';
			break;
	}

	$arr = array();

	foreach( $keyword_array as $keyword ) 
	{
		$keyword = trim($keyword);
		if ( $keyword ) {
			$arr[] = $this->build_where_keyword_single( $keyword, $name ) ;
		}
	}

	if ( is_array( $arr ) && count( $arr ) ) {
		$glue  = ' '. $andor_glue .' ';
		$where = ' ( '. implode( $glue , $arr ) .' ) ' ;
		return $where;
	}

	return null;
}

function build_where_keyword_single( $str, $name )
{
	$text = $name ." LIKE '%" . addslashes( $str ) . "%'" ;
	return $text;
}

//---------------------------------------------------------
// permission
//---------------------------------------------------------
function build_id_array_with_perm( $id_array, $name, $groups=null )
{
	$arr = array();
	foreach ( $id_array as $id ) 
	{
		if ( $this->check_perm_by_id_name_groups( $id, $name, $groups ) ) {
			$arr[] = $id ;
		}
	}
	return $arr;
}

function build_rows_with_perm( $rows, $name, $groups=null )
{
	$arr = array();
	foreach ( $rows as $row ) 
	{
		if ( $this->check_perm_by_row_name_groups( $row, $name, $groups ) ) {
			$arr[] = $row ;
		}
	}
	return $arr;
}

function check_perm_by_id_name_groups( $id, $name, $groups=null )
{
	$row = $this->get_cached_row_by_id( $id ) ;
	return $this->check_perm_by_row_name_groups( $row, $name, $groups );
}

function check_perm_by_row_name_groups( $row, $name, $groups=null )
{
	if ( ! isset( $row[ $name ] ) ) {
		return false ;
	}

	$val = $row[ $name ] ;

	if ( $this->_PERM_ALLOW_ALL && ( $val == $this->_PERM_ALLOW_ALL ) ) {
		return true;
	}

	if ( $this->_PERM_DENOY_ALL && ( $val == $this->_PERM_DENOY_ALL ) ) {
		return false;
	}

	$perms = $this->str_to_array( $val, $this->_PERM_SEPARATOR );
	return $this->check_perms_in_groups( $perms, $groups );
}

function check_perm_by_perm_groups( $perm, $groups=null )
{
	if ( $this->_PERM_ALLOW_ALL && ( $perm == $this->_PERM_ALLOW_ALL ) ) {
		return true;
	}

	if ( $this->_PERM_DENOY_ALL && ( $perm == $this->_PERM_DENOY_ALL ) ) {
		return false;
	}

	$perms = $this->str_to_array( $perm, $this->_PERM_SEPARATOR );
	return $this->check_perms_in_groups( $perms, $groups );
}

function check_perms_in_groups( $perms, $groups=null )
{
	if ( !is_array($perms) || !count($perms) ) {
		return false;
	}

	if ( empty($groups) ) {
		$groups = $this->_xoops_groups ;
	}

	$arr = array_intersect( $groups, $perms );
	if ( is_array($arr) && count($arr) ) {
		return true;
	}
	return false;
}

function get_perm_array_by_row_name( $row, $name )
{
	if ( isset( $row[ $name ] ) ) {
		return $this->get_perm_array( $row[ $name ] );
	} else {
		return array() ;
	}
}

function get_perm_array( $val )
{
	return $this->str_to_array( $val, $this->_PERM_SEPARATOR );
}

//---------------------------------------------------------
// selbox
//---------------------------------------------------------
function build_form_selbox( $name='', $value=0, $none=0, $onchange='' )
{
	return $this->build_form_select_list(
		$this->get_rows_by_orderby( $this->_title_name ), 
		$this->_title_name, $value, $none, $name, $onchange );
}

function build_form_select_list( $rows, $title_name='', $preset_id=0, 
	$none=0, $sel_name='', $onchange='' )
{
	if ( empty($title_name) ) {
		$title_name = $this->_title_name;
	}

	if ( empty($sel_name) ) {
		$sel_name = $this->_id_name;
	}

	$str = '<select name="'. $sel_name .'" ';
	if ( $onchange != "" ) {
		$str .= ' onchange="'. $onchange .'" ';
	}
	$str .= ">\n";

	if ( $none ) {
		$str .= '<option value="0">';
		$str .= $this->_NONE_VALUE ;
		$str .= "</option>\n";
	}

// Warning : Invalid argument supplied for foreach() 
	if ( is_array($rows) && count($rows) ) {
		foreach ( $rows as $row )
		{
			$id     = $row[ $this->_id_name ];
			$title  = $row[ $title_name ];
			$prefix = '' ;

			if ( $this->_use_prefix ) {
				$prefix = $row[ $this->_PREFIX_NAME ] ;

				if ( $prefix ) {
					$prefix = str_replace( $this->_PREFIX_MARK, $this->_PREFIX_BAR, $prefix ).' ';
				}
			}

			$sel = '';
			if ( $id == $preset_id ) {
				$sel = ' selected="selected" ';
			}

			$str .= '<option value="'. $id .'" '. $sel .'>';
			$str .= $prefix . $this->sanitize($title);
			$str .= "</option>\n";
		}
	}

	$str .=  "</select>\n";
	return $str;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function str_to_array( $str, $pattern )
{
	$arr1 = explode( $pattern, $str );
	$arr2 = array();
	foreach ( $arr1 as $v )
	{
		$v = trim($v);
		if ($v == '') { continue; }
		$arr2[] = $v;
	}
	return $arr2;
}

function array_to_str( $arr, $glue )
{
	$val = false;
	if ( is_array($arr) && count($arr) ) {
		$val = implode($glue, $arr);
	}
	return $val;
}

function array_to_perm( $arr, $glue )
{
	$val = $this->array_to_str( $arr, $glue );
	if ( $val ) {
		$val = $glue . $val . $glue ;
	}
	return $val;
}

function sanitize_array_int( $arr_in )
{
	if ( !is_array($arr_in) || !count($arr_in) ) {
		return null;
	}

	$arr_out = array();
	foreach ( $arr_in as $in ) {
		$arr_out[] = intval($in);
	}
	return $arr_out;
}

//---------------------------------------------------------
// xoops groups
//---------------------------------------------------------
function get_xoops_groups()
{
	global $xoopsUser;
	if ( is_object($xoopsUser) ) {
		return $xoopsUser->getGroups() ;
	}
	return array( XOOPS_GROUP_ANONYMOUS );
}

//----- class end -----
}

?>