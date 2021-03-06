<?php
// $Id: map.php,v 1.2 2012/04/09 11:52:19 ohwada Exp $

// 2012-04-02 K.OHWADA
// set_title_length()

//=========================================================
// webmap3 module
// 2012-03-01 K.OHWADA
//=========================================================

//=========================================================
// class webmap3_api_map
//=========================================================
class webmap3_api_map
{
	var $_gicon_handler;
	var $_header_class;
	var $_language_class ;
	var $_multibyte_class ;
	var $_utility_class ;

// MUST uniq
	var $_map_div_id  = '';
	var $_map_func    = '';
	var $_gicon_func  = '';
	var $_simple_map_div  = '' ;
	var $_simple_map_func = '' ;

// config
	var $_REGION_DEFAULT = '';
	var $_region         = '';
	var $_api_key        = '';

// center
	var $_latitude  = 0;
	var $_longitude = 0;
	var $_zoom      = 0;

// map param
	var $_map_type_control            = true;
	var $_zoom_control                = true;
	var $_pan_control                 = true;
	var $_street_view_control         = true;
	var $_scale_control               = false;
	var $_overview_map_control        = false;
	var $_overview_map_control_opened = false;

	var $_map_type_control_style = '';
	var $_zoom_control_style     = ''; 
	var $_map_type               = '';

	var $_use_draggable_marker = false ;
	var $_use_center_marker    = false ;
	var $_use_search_marker    = false ;
	var $_use_current_location = true;
	var $_use_current_address  = false;
	var $_use_parent_location  = false;

	var $_opener_mode = '';
	var $_timeout     = 0;

	var $_map_width  = '';
	var $_map_height = '';
	var $_markers = array();

// info window
	var $_title_lengh = 0;
	var $_title_sanitize = true;
	var $_info_max   = 0 ;
	var $_info_width = 0 ;
	var $_info_break = '' ;
	var $_info_sanitize = true ;
	var $_image_max_width  = 0 ;
	var $_image_max_height = 0 ;
	var $_image_flag_zero  = true ;

// lang
	var $_lang_latitude ;
	var $_lang_longitude ;
	var $_lang_zoom ;
	var $_lang_no_match_place ;
	var $_lang_not_compatible ;
	var $_lang_not_successful ;

// direname ;
	var $_WEBMAP3_DIRNAME ;
	var $_MARKER_URL ;
	var $_direname ;
	var $_gicon_name ;

// element id
	var $_ele_id_list             = '';
	var $_ele_id_search           = '';
	var $_ele_id_current_location = '';
	var $_ele_id_current_address  = '';
	var $_ele_id_parent_latitude  = '';
	var $_ele_id_parent_longitude = '';
	var $_ele_id_parent_zoom      = '';
	var $_ele_id_parent_address   = '';

// tmplate
	var $_tmplate_gicon_array_js  = '' ;
	var $_tmplate_geoxml_head_js  = '' ;
	var $_tmplate_markers_head_js = '' ;
	var $_tmplate_search_head_js  = '' ;
	var $_tmplate_body_common_js  = '' ;
	var $_tmplate_block_common_js = '' ;
	var $_tmplate_get_location_head_js = '' ;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webmap3_api_map( $dirname )
{
	$this->_WEBMAP3_DIRNAME = $dirname;

	$this->_gicon_handler   =& webmap3_handler_gicon::getSingleton( $dirname );
	$this->_header_class    =& webmap3_xoops_header::getSingleton(  $dirname );
	$this->_language_class  =& webmap3_d3_language::getSingleton(   $dirname );
	$this->_multibyte_class =& webmap3_lib_multibyte::getInstance();
	$this->_utility_class   =& webmap3_lib_utility::getInstance();

	$config_class =& webmap3_inc_config::getSingleton( $dirname );
	$this->_REGION_DEFAULT = $config_class->get_by_name( 'region' );
	$this->_api_key = $config_class->get_by_name( 'api_key' );
}

public static function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webmap3_api_map( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// init
//---------------------------------------------------------
function init()
{

// MUST set by caller
	$this->_map_div_id = '';
	$this->_map_func   = '';

// config
	$this->_region = $this->_REGION_DEFAULT;

// Yokohama, Japan
	$this->_latitude  = _C_WEBMAP3_CFG_LATITUDE;
	$this->_longitude = _C_WEBMAP3_CFG_LONGITUDE;
	$this->_zoom      = _C_WEBMAP3_CFG_ZOOM;

// map param
	$this->_map_type_control            = true;
	$this->_zoom_control                = true;
	$this->_pan_control                 = true;
	$this->_street_view_control         = true;
	$this->_scale_control               = false;
	$this->_overview_map_control        = false;
	$this->_overview_map_control_opened = false;

	$this->_map_type_control_style = _C_WEBMAP3_GOOGLE_MAP_TYPE_CONTROL_STYLE;
	$this->_zoom_control_style     = _C_WEBMAP3_GOOGLE_ZOOM_CONTROL_STYLE; 
	$this->_map_type               = _C_WEBMAP3_GOOGLE_MAP_TYPE;

	$this->_use_draggable_marker = false ;
	$this->_use_center_marker    = false ;
	$this->_use_search_marker    = false ;
	$this->_use_current_location = true;
	$this->_use_current_address  = false;
	$this->_use_parent_location  = false;

	$this->_opener_mode = '';
	$this->_markers = array();

	$this->_map_width  = _C_WEBMAP3_MAP_WIDTH;
	$this->_map_height = _C_WEBMAP3_MAP_HEIGHT;
	$this->_timeout    = _C_WEBMAP3_MAP_TIMEOUT;

// info window
	$this->_title_lengh = _C_WEBMAP3_MAP_TITLE_LENGH;
	$this->_title_sanitize = true;
	$this->_info_max   = _C_WEBMAP3_MAP_INFO_MAX ;
	$this->_info_width = _C_WEBMAP3_MAP_INFO_WIDTH ;
	$this->_info_break = _C_WEBMAP3_MAP_INFO_BREAK ;
	$this->_info_sanitize = true ;
	$this->_image_max_width  = _C_WEBMAP3_MAP_IMAGE_MAX_WIDTH ;
	$this->_image_max_height = _C_WEBMAP3_MAP_IMAGE_MAX_HEIGHT ;
	$this->_image_flag_zero  = true ;

// language
	$this->set_lang_latitude(  $this->get_lang( 'latitude' ) ) ;
	$this->set_lang_longitude( $this->get_lang( 'longitude' ) ) ;
	$this->set_lang_zoom(      $this->get_lang( 'zoom' ) ) ;
	$this->set_lang_not_compatible( $this->get_lang( 'not_compatible' ) ) ;
	$this->set_lang_no_match_place( $this->get_lang( 'no_match_place' ) ) ;
	$this->set_lang_not_successful( $this->get_lang( 'not_successful' ) ) ;

// dirname
	$dirname = $this->_WEBMAP3_DIRNAME;
	$this->_dirname = $dirname;
	$this->_MARKER_URL = XOOPS_URL .'/modules/'. $dirname .'/images/markers';

	$this->_simple_map_div   = $dirname.'_map_simple_0' ;
	$this->_simple_map_func  = $dirname.'_load_map_simple_0' ;
	$this->_gicon_name = $dirname.'_get_gicon_array' ;
	$this->_gicon_func       = $dirname.'_get_gicon_array';

// element id
	$this->_ele_id_list             = $dirname .'_map_list';
	$this->_ele_id_search           = $dirname .'_map_search';
	$this->_ele_id_current_location = $dirname .'_map_current_location';
	$this->_ele_id_current_address  = $dirname .'_map_current_address';
	$this->_ele_id_parent_latitude  = $dirname .'_map_parent_latitude';
	$this->_ele_id_parent_longitude = $dirname .'_map_parent_longitude';
	$this->_ele_id_parent_zoom      = $dirname .'_map_parent_zoom';
	$this->_ele_id_parent_address   = $dirname .'_map_parentt_address';

// tmplate
	$this->_tmplate_gicon_array_js   = 'db:'. $dirname .'_inc_gicon_array_js.html' ;
	$this->_tmplate_geoxml_head_js   = 'db:'. $dirname .'_inc_geoxml_head_js.html' ;
	$this->_tmplate_markers_head_js  = 'db:'. $dirname .'_inc_markers_head_js.html' ;
	$this->_tmplate_search_head_js   = 'db:'. $dirname .'_inc_search_head_js.html' ;
	$this->_tmplate_body_common_js   = 'db:'. $dirname .'_inc_body_common_js.html' ;
	$this->_tmplate_block_common_js  = 'db:'. $dirname .'_inc_block_common_js.html' ;
	$this->_tmplate_get_location_head_js = 'db:'. $dirname .'_inc_get_location_head_js.html' ;
}

//---------------------------------------------------------
// simple map
//---------------------------------------------------------
function build_simple_map()
{
	$this->assign_google_map_js_to_head();
	$this->assign_map_js_to_head();
	$this->assign_gicon_array_to_head();

	$this->set_map_div_id( $this->_simple_map_div ) ;
	$this->set_map_func(   $this->_simple_map_func ) ;

	$param = $this->build_markers( $this->_markers );
	         $this->fetch_markers_head( $param );
	$js    = $this->fetch_body_common(  $param );

	$str   = $js;
	$str  .= "\n";
	$str  .= $this->build_map_div( $this->_simple_map_div, $this->_map_width, $this->_map_height );

	return $str;
}

function build_map_div( $id, $width, $height )
{
	$str = '<div id="'.$id.'" style="width:'.$width.';height:'.$height.';">Loading ...</div>';
	return $str;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function assign_gicon_array_to_head( $flag_head=true )
{
	if ( $this->check_once_name( $this->_gicon_name ) ) {
		$gicons = $this->get_gicons();
		$param  = $this->build_gicons( $gicons );
		$js     = $this->fetch_gicon_array_head( $param, $flag_head );
		return $js;
	}
	return null;
}

function get_gicons( $limit=0, $offset=0 )
{
	return $this->_gicon_handler->get_icons( $limit, $offset );
}

function build_gicons( $gicns )
{
	$arr = array(
		'gicon_func' => $this->_gicon_func ,
		'map_gicons' => $gicns ,
	);
	return $arr;
}

function build_geoxml( $xml_url )
{
	$arr = $this->build_param_common();
	$arr['xml_url'] = $xml_url ;
	return $arr;
}

function build_markers( $markers )
{
	$arr = $this->build_param_common();
	$arr['map_markers'] = $this->escape_markers( $markers ) ;
	return $arr;
}

function escape_markers( $markers )
{
	if ( !is_array($markers) || !count($markers) ) {
		return $markers;
	}

	$arr = array();
	foreach ( $markers as $marker )
	{
		if ( isset($marker['info']) ) {
			$marker['info'] = $this->escape_single_quote( $marker['info'] );
		}
		$arr[] = $marker;
	}
	return $arr;
}

function escape_single_quote( $str )
{
	$str = str_replace( "\'", "'",  $str );
	$str = str_replace( "'",  "\'", $str );
	return $str;
}

function build_search()
{
	$arr = $this->build_param_common();
	return $arr;
}

function build_get_location()
{
	$arr = $this->build_param_common( false );
	return $arr;
}

function fetch_gicon_array_head( $param, $flag_head=true )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$js = $tpl->fetch( $this->_tmplate_gicon_array_js );

	if ( $flag_head ) {
		$this->assign_to_head( $js );
	}

	return $js;
}

function fetch_geoxml_head( $param, $flag_head=true )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$js = $tpl->fetch( $this->_tmplate_geoxml_head_js );

	if ( $flag_head ) {
		$this->assign_to_head( $js );
	}

	return $js;
}

function fetch_markers_head( $param, $flag_head=true )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$js = $tpl->fetch( $this->_tmplate_markers_head_js );

	if ( $flag_head ) {
		$this->assign_to_head( $js );
	}

	return $js;
}

function fetch_search_head( $param, $flag_head=true )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$js = $tpl->fetch( $this->_tmplate_search_head_js );

	if ( $flag_head ) {
		$this->assign_to_head( $js );
	}

	return $js;
}

function fetch_get_location_head( $param, $flag_head=false )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	$js = $tpl->fetch( $this->_tmplate_get_location_head_js );

	if ( $flag_head ) {
		$this->assign_to_head( $js );
	}

	return $js;
}

function fetch_body_common( $param )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	return $tpl->fetch( $this->_tmplate_body_common_js );
}

function fetch_block_common( $param )
{
	$tpl = new XoopsTpl();
	$tpl->assign( $param );
	return $tpl->fetch( $this->_tmplate_block_common_js );
}

function build_param_common( $flag_header=true )
{
	$arr = array(
		'map_div_id' => $this->_map_div_id ,
		'map_func'   => $this->_map_func ,
		'gicon_func' => $this->_gicon_func ,

		'xoops_langcode'     => _LANGCODE ,
		'webmap3_dirname'    => $this->_WEBMAP3_DIRNAME ,
		'webmap3_marker_url' => $this->_MARKER_URL ,
		'dirname'            => $this->_dirname ,
		'webmap3_api_key'    => $this->_api_key ,

// center
		'latitude'                   => $this->_latitude ,
		'longitude'                  => $this->_longitude ,
		'zoom'                       => $this->_zoom ,

// map param
		'map_type_control'            => $this->bool_to_str( $this->_map_type_control ),
		'zoom_control'                => $this->bool_to_str( $this->_zoom_control ),
		'pan_control'                 => $this->bool_to_str( $this->_pan_control ),
		'street_view_control'         => $this->bool_to_str( $this->_street_view_control ),
		'scale_control'               => $this->bool_to_str( $this->_scale_control ),
		'overview_map_control'        => $this->bool_to_str( $this->_overview_map_control ),
		'overview_map_control_opened' => $this->bool_to_str( $this->_overview_map_control_opened ),
		'map_type_control_style'      => $this->_map_type_control_style ,
		'map_type'                    => $this->_map_type ,
		'zoom_control_style'          => $this->_zoom_control_style ,

		'use_draggable_marker' => $this->bool_to_str( $this->_use_draggable_marker ) ,
		'use_center_marker'    => $this->bool_to_str( $this->_use_center_marker ) ,
		'use_search_marker'    => $this->bool_to_str( $this->_use_search_marker ) ,
		'use_current_location' => $this->bool_to_str( $this->_use_current_location ) ,
		'use_current_address'  => $this->bool_to_str( $this->_use_current_address ) ,
		'use_parent_location'  => $this->bool_to_str( $this->_use_parent_location ) ,

		'opener_mode' => $this->_opener_mode ,
		'timeout'     => $this->_timeout ,
		'region'      => $this->_region ,

// element
		'ele_id_list'             => $this->_ele_id_list ,
		'ele_id_search'           => $this->_ele_id_search ,
		'ele_id_current_location' => $this->_ele_id_current_location ,
		'ele_id_current_address'  => $this->_ele_id_current_address ,
		'ele_id_parent_latitude'  => $this->_ele_id_parent_latitude ,
		'ele_id_parent_longitude' => $this->_ele_id_parent_longitude ,
		'ele_id_parent_zoom'      => $this->_ele_id_parent_zoom ,
		'ele_id_parent_address'   => $this->_ele_id_parent_address ,

// lang
		'lang_latitude'       => $this->_lang_latitude ,
		'lang_latitude'       => $this->_lang_latitude ,
		'lang_longitude'      => $this->_lang_longitude ,
		'lang_zoom'           => $this->_lang_zoom ,
		'lang_no_match_place' => $this->_lang_no_match_place ,
		'lang_not_compatible' => $this->_lang_not_compatible ,
		'lang_not_successful' => $this->_lang_not_successful ,
	);

	return $arr;
}

function bool_to_str( $bool )
{
	if ( $bool ) {
		return "true";
	}
	return "false";
}

//---------------------------------------------------------
// marker
//---------------------------------------------------------
function clear_marker()
{
	$this->_markers[] = array();
}

function add_marker( $lat, $lng, $info='', $id=0 )
{
	$this->_markers[] = $this->build_single_marker( $lat, $lng, $info, $id ) ;
}

function build_single_marker( $lat, $lng, $info='', $id=0 )
{
	$marker = array(
		'latitude'  => floatval($lat) ,
		'longitude' => floatval($lng) ,
		'info'      => $info ,
		'icon_id'   => intval($id) ,
	);
	return $marker ;
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function adjust_image_size( $width, $height )
{
	return $this->_utility_class->adjust_image_size( 
		$width, $height, 
		$this->_image_max_width, $this->_image_max_height, 
		$this->_image_flag_zero );
}

//---------------------------------------------------------
// multibyte
//---------------------------------------------------------
function build_title_short( $str )
{
	$str = $this->_multibyte_class->shorten( $str, $this->_title_lengh );
	if ( $this->_title_sanitize ) {
		$str = $this->sanitize( $str );
	}
	return $str;
}

function build_summary( $str )
{
	return $this->_multibyte_class->build_summary_with_wordwrap( 
		$str, $this->_info_max, $this->_info_width, $this->_info_break, $this->_info_sanitize );
}

function sanitize( $str )
{
	return $this->_multibyte_class->sanitize( $str );
}

//---------------------------------------------------------
// header
//---------------------------------------------------------
function assign_google_map_js_to_head( $flag_header=true )
{
	return $this->_header_class->assign_or_get_google_map_js( $flag_header, $this->_api_key ) ;
}

function assign_map_js_to_head( $flag_header=true )
{
	return $this->_header_class->assign_or_get_js( 'map', $flag_header ) ;
}

function assign_search_js_to_head( $flag_header=true )
{
	return $this->_header_class->assign_or_get_js( 'search', $flag_header ) ;
}

function assign_to_head( $var )
{
	$this->_header_class->assign_xoops_module_header( $var ) ;
}

function check_once_name( $name )
{
	return $this->_header_class->check_once_name( $name ) ;
}

//---------------------------------------------------------
// language
//---------------------------------------------------------
function get_lang( $name )
{
	return $this->_language_class->get_constant( $name );
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_dirname( $val )
{
	$this->_dirname = $val;
}

function set_map_div_id( $val )
{
	$this->_map_div_id = $val ;
}

function set_map_func( $val )
{
	$this->_map_func = $val ;
}

function set_timeout( $val )
{
	$this->_timeout = intval($val);
}

function set_region( $val )
{
	$this->_region = $val;
}

function set_opener_mode( $val )
{
	$this->_opener_mode = $val;
}

function set_latitude( $val )
{
	$this->_latitude = floatval($val);
}

function set_longitude( $val )
{
	$this->_longitude = floatval($val);
}

function set_zoom( $val )
{
	$this->_zoom = intval($val);
}

function set_element( $val )
{
	$this->_element = $val;
}

function set_map_type_control( $val )
{
	$this->_map_type_control = (bool)$val;
}

function set_zoom_control( $val )
{
	$this->_zoom_control = (bool)$val;
}

function set_pan_control( $val )
{
	$this->_pan_control = (bool)$val;
}

function set_street_view_control( $val )
{
	$this->_street_view_control = (bool)$val;
}

function set_scale_control( $val )
{
	$this->_scale_control = (bool)$val;
}

function set_overview_map_control( $val )
{
	$this->_overview_map_control = (bool)$val;
}

function set_overview_map_control_opened( $val )
{
	$this->_overview_map_control_opened = (bool)$val;
}

function set_map_type_control_style( $val )
{
	$this->_map_type_control_style = $val;
}

function set_zoom_control_style( $val )
{
	$this->_zoom_control_style = $val;
}

function set_map_type( $val )
{
	$this->_map_type = $val;
}

function set_use_draggable_marker( $val )
{
	$this->_use_draggable_marker = (bool)$val;
}

function set_use_center_marker( $val )
{
	$this->_use_center_marker = (bool)$val;
}

function set_use_search_marker( $val )
{
	$this->_use_search_marker = (bool)$val;
}

function set_ele_id_list( $val )
{
	$this->_ele_id_list = $val;
}

function set_ele_id_search( $val )
{
	$this->_ele_id_search = $val;
}

function set_ele_id_current_location( $val )
{
	$this->_ele_id_current_location = $val;
}

function set_ele_id_current_address( $val )
{
	$this->_ele_id_current_address = $val;
}

function set_ele_id_parent_latitude( $val )
{
	$this->_ele_id_parent_latitude = $val;
}

function set_ele_id_parent_longitude( $val )
{
	$this->_ele_id_parent_longitude = $val;
}

function set_ele_id_parent_zoom( $val )
{
	$this->_ele_id_parent_zoom = $val;
}

function set_ele_id_parent_address( $val )
{
	$this->_ele_id_parent_address = $val;
}

function set_use_current_location( $val )
{
	$this->_use_current_location = (bool)$val;
}

function set_use_current_address( $val )
{
	$this->_use_current_address = (bool)$val;
}

function set_use_parent_location( $val )
{
	$this->_use_parent_location = (bool)$val;
}

function set_lang_latitude( $val )
{
	$this->_lang_latitude = $val ;
}

function set_lang_longitude( $val )
{
	$this->_lang_longitude = $val ;
}

function set_lang_zoom( $val )
{
	$this->_lang_zoom = $val ;
}

function set_lang_not_compatible( $val )
{
	$this->_lang_not_compatible = $val ;
}

function set_lang_no_match_place( $val )
{
	$this->_lang_no_match_place = $val ;
}

function set_lang_not_successful( $val )
{
	$this->_lang_not_successful = $val ;
}

function set_title_length( $val )
{
	$this->_title_length = intval($val) ;
}

function set_title_sanitize( $val )
{
	$this->_title_sanitize = (bool)($val) ;
}

function set_info_sanitize( $val )
{
	$this->_info_sanitize = (bool)($val) ;
}

function set_info_max( $val )
{
	$this->_info_max = intval($val) ;
}

function set_info_width( $val )
{
	$this->_info_width = intval($val) ;
}

function set_info_break( $val )
{
	$this->_info_break = $val ;
}

function set_image_max_width( $val )
{
	$this->_image_max_width = intval($val) ;
}

function set_image_max_height( $val )
{
	$this->_image_max_height = intval($val) ;
}

function set_map_size( $w, $h )
{
	$this->set_map_width(  $w );
	$this->set_map_height( $h );
}

function set_map_width( $v, $u='px' )
{
	$this->_map_width = intval($v).$u;
}

function set_map_height( $v, $u='px' )
{
	$this->_map_height = intval($v).$u;
}

function set_map_center( $lat, $lng, $zoom )
{
	$this->set_latitude( $lat );
	$this->set_longitude( $lng );
	$this->set_zoom( $zoom );
}

function set_gicon_name( $val )
{
	$this->_gicon_name = $val ;
}

function set_gicon_func( $val )
{
	$this->_gicon_func = $val ;
}

function set_tmplate_geoxml_head_js( $val )
{
	$this->_tmplate_geoxml_head_js = $val ;
}

function set_tmplate_markers_head_js( $val )
{
	$this->_tmplate_markers_head_js = $val ;
}

function set_tmplate_search_head_js( $val )
{
	$this->_tmplate_search_head_js = $val ;
}

function set_tmplate_get_location_head_js( $val )
{
	$this->_tmplate_get_location_head_js = $val ;
}

function set_tmplate_body_common_js( $val )
{
	$this->_tmplate_body_common_js = $val ;
}

function set_tmplate_block_common_js( $val )
{
	$this->_tmplate_block_common_js = $val ;
}

// --- class end ---
}
?>