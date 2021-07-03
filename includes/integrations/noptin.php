<?php
/**
 * Contains the main noptin class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Noptin integration Class.
 *
 */
class Hizzle_reCAPTCHA_Noptin_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'before_print_noptin_submit_button', array( $this, 'show_if_not_single_line' ) );
		add_action( 'after_print_noptin_form_fields', array( $this, 'show_if_single_line' ) );
		add_action( 'before_noptin_quick_widget_submit', array( $this, 'display' ) );
		add_filter( 'render_block', array( $this, 'filter_block_output' ), 10, 2 );
		add_action( 'noptin_before_add_ajax_subscriber', array( $this, 'verify_token' ) );
	}

	/**
	 * Displays reCAPTCHA if this is a single line field.
	 *
	 * @since 1.0.0
	 */
	public function show_if_single_line( $is_single_line ) {
		if ( $is_single_line ) {
			$this->display();
		}
	}

	/**
	 * Displays reCAPTCHA if this is not a single line field.
	 *
	 * @since 1.0.0
	 */
	public function show_if_not_single_line( $is_single_line ) {
		if ( ! $is_single_line ) {
			$this->display();
		}
	}

	/**
	 * Filters block output.
	 *
	 * @since 1.0.0
	 */
	public function filter_block_output( $block_content, $block ) {
		if ( 'noptin/email-optin' === $block['blockName'] ) {
			$block_content = str_replace( 'required/>', 'required/>' . $this->get_html(), $block_content );
		}
		return $block_content;
	}

	/**
	 * Handles the submission of comments.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			echo esc_html( $error->get_error_message() );
			exit;
	    }

	}

}
