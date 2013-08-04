<?php

/**
 * We use Post Formats a lot, let's add that as a filter on the All Posts page
 */
function homeroom_post_formats_select( $checks = true, $all_label = false ) {
	if ( $checks ) {
		if ( !current_theme_supports( 'post-formats' ) )
			return;

		if ( 'post' !== get_current_screen()->post_type )
			return;
	}

	$all_label = !$all_label ? __( 'All Formats', 'homeroom' ) : $all_label;

	$post_formats = get_theme_support( 'post-formats' );
	if ( is_array( $post_formats[0] ) ) :
	?><select name="post_format" id="post_format">
	<option value="0"><?php _e( $all_label, 'homeroom' ); ?></option>
	<?php foreach ( $post_formats[0] as $format ): ?>
	<option<?php selected( isset( $_REQUEST['post_format'] ) && $_REQUEST['post_format'] == $format ); ?> value="<?php echo esc_attr( $format ); ?>"><?php echo esc_html( get_post_format_string( $format ) ); ?></option>
	<?php endforeach; ?>
	</select><?php
	endif;
}
add_action( 'restrict_manage_posts', 'homeroom_post_formats_select' );

// Handler to implement format filters (see above)
function homeroom_manage_posts_formats( &$wp_query ) {
	if ( !is_admin() )
		return;

	if ( function_exists( 'get_current_screen' ) && $screen = get_current_screen() ) {
		if ( is_object( $screen ) && 'post' !== $screen->post_type )
			return;
	}

	if ( empty( $_REQUEST['post_format'] ) )
		return;

	$post_format_tax_query = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => 'post-format-' . esc_attr( $_REQUEST['post_format'] ),
		'operator' => 'IN'
	);
	$tax_query = $wp_query->get( 'tax_query' );
	if ( is_array( $tax_query ) ) {
		$tax_query = $tax_query + $post_format_tax_query;
	} else {
		$tax_query = array( $post_format_tax_query );
	}
	$wp_query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'homeroom_manage_posts_formats' );

/**
 * We add some extra settings to Settings > Discussion, because Homeroom introduces some new
 * functionality in that area.
 */
function homeroom_discussion_settings() {
 	register_setting( 'discussion', 'highlight_commenter_emails', 'highlight_commenter_emails_validate' );
 	add_settings_field( 'highlight_commenter_emails', __( 'Highlight user comments', 'homeroom' ), 'homeroom_comment_emails', 'discussion', 'default' );
}
add_action( 'admin_init', 'homeroom_discussion_settings' );

function homeroom_comment_emails() {
	?><input name="highlight_commenter_emails" type="text" id="highlight_commenter_emails" value="<?php echo esc_attr( get_option( 'highlight_commenter_emails' ) ); ?>" class="regular-text" />
	<p class="description"><?php echo esc_html( __( 'Enter a space-separated list of email addresses to highlight any comments made by those users.', 'homeroom' ) ); ?></p><?php
}

function highlight_commenter_emails_validate( $val ) {
	$val = explode( ' ', $val );
	$out = array();
	foreach ( (array) $val as $email ) {
		if ( is_email( $email ) )
			$out[] = strtolower( $email );
	}
	return implode( ' ', $out );
}
