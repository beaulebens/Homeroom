<?php
/**
 * The template for displaying search forms in Homeroom
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
		<label for="s" class="assistive-text"><?php _e( 'Search', 'homeroom' ); ?></label>
		<input type="text" class="field" name="s" id="s" placeholder="<?php echo esc_attr( __( 'Search &hellip;', 'homeroom' ) ); ?>" value="<?php echo !empty( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>" />
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'homeroom' ); ?>" />
	</form>
