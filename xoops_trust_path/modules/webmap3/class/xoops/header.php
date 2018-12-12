<?php
// $Id: header.php,v 1.1 2012/03/17 09:28:12 ohwada Exp $

//=========================================================
// webmap3 module
// 2012-03-01 K.OHWADA
//=========================================================

if( ! defined( 'XOOPS_TRUST_PATH' ) ) die( 'not permit' ) ;

//=========================================================
// class webmap3_xoops_header
//=========================================================
class webmap3_xoops_header extends webmap3_xoops_header_base
{
	var $_MAP_JS   = 'map';
	var $_GICON_JS = 'gicon';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function webmap3_xoops_header( $dirname )
{
	$this->webmap3_xoops_header_base( $dirname, WEBMAP3_TRUST_DIRNAME );
}

public static function &getSingleton( $dirname )
{
	static $singletons;
	if ( !isset( $singletons[ $dirname ] ) ) {
		$singletons[ $dirname ] = new webmap3_xoops_header( $dirname );
	}
	return $singletons[ $dirname ];
}

//---------------------------------------------------------
// assign
//---------------------------------------------------------
function assign_or_get_default_css( $flag_header=true )
{
	$CSS_CONST = 'default_css' ;
	$CSS_FILE  = 'default.css' ;

	$show = false ;
	$css  = null ;

	if ( $this->check_once_name( $CSS_CONST ) ) {
		$show = true ;
		$css  = $this->build_link_css_libs( $CSS_FILE );
	}

	if ( $flag_header && $this->_FLAG_ASSIGN_HEADER ) {
		$this->assign_xoops_module_header( $css );
		$show = false ;
	}

	return array( $show, $css );
}

function assign_or_get_google_map_js( $flag_header=true, $api_key='' )
{
	$show = false ;
	$js   = null ;

	if ( $this->check_gmap_api() ) {
		$show = true ;
		$js   = $this->build_gmap_api('', $api_key);
	}

	if ( $flag_header && $this->_FLAG_ASSIGN_HEADER ) {
		$this->assign_xoops_module_header( $js );
		$show = false ;
	}

	return array( $show, $js );
}

function assign_or_get_js( $key, $flag_header=true )
{
	$show = false ;
	$js   = null ;

	if ( $this->check_name_js( $key ) ) {
		$show = true ;
		$js   = $this->build_name_js( $key );
	}

	if ( $flag_header && $this->_FLAG_ASSIGN_HEADER ) {
		$this->assign_xoops_module_header( $js );
		$show = false ;
	}

	return array( $show, $js );
}

//--------------------------------------------------------
// google map js
//--------------------------------------------------------
function build_once_gmap_api( $langcode=null , $api_key='' )
{
	if ( $this->check_gmap_api() ) {
		return $this->build_gmap_api( $langcode , $api_key ) ;
	}
	return null;
}

function build_gmap_api( $langcode=null , $api_key='' )
{
	if ( empty($langcode) ) {
		$langcode = _LANGCODE ;
	}

	$src = 'https://maps.google.com/maps/api/js?v=3&amp;key='.$api_key.'&amp;sensor=false&amp;language='. $langcode  ;
	$str = '<script src="'. $src .'" type="text/javascript" charset="utf-8"></script>'."\n";
	return $str;
}

function check_gmap_api()
{
	return $this->check_once_name( "GMAP_API_V3" );
}

// --- class end ---
}

?>