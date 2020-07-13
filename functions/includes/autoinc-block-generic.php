<?php
/**
 * Created by PhpStorm.
 * User: Edward Nickerson
 * Date: 24/07/2019
 * Time: 07:55
 */

add_action( 'acf/init', 'acfgbc_BlockGenericSection' );
function acfgbc_BlockGenericSection() {
	if ( ! function_exists( 'acf_register_block' ) ) {
		return;
	}
	acf_register_block( array(
		'name'            => 'acfgbcBlockGenericSection',
		'title'           => __( 'Block – Generic Section' ),
		'description'     => __( 'Block – Generic Section' ),
		'render_callback' => 'acfgbc_BlockGenericSection_rc',
		'category'        => 'technickswpwordpresstheme',
		'icon'            => 'editor-code',
		'mode'            => 'edit',
		'supports'        => array( 'align' => false, 'multiple' => true, ),
		'keywords'        => array( 'Row', 'Common' ),
	) );
}

function acfgbc_BlockGenericSection_rc( $block, $content = '', $is_preview = false ) {
	if ( $is_preview ) {
		include_once get_template_directory() . '/parts/blocks/editor/styles.php';
	}
	include get_template_directory() . '/parts/blocks/BlockGenericSection.php';
}