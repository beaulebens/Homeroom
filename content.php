<?php
/**
 * @package Homeroom
 * @since Homeroom 1.0
 */
?>

<?php $this_post_format = get_post_format(); ?>
<article class="shadow f-<?php echo esc_attr( $this_post_format ); ?>">
	<?php
	$this_post_format = get_post_format();
	get_template_part( 'snippet', $this_post_format );

	// On the homepage, we do some lookahead magic and collapse posts a bit
	// Don't collapse images, they look better in their own bubbles
	// Break for each new day also
	if ( is_home() && 'image' != $this_post_format ) {
		$temp_post = $post;
		$first = true;
		while ( true ) {
			$next = homeroom_next_post();

			// Different post format, or nothing else to output?
			if ( !$next || $this_post_format != get_post_format( $next ) )
				break;

			// Different day?
			if ( get_the_date( 'Y-m-d' ) != substr( $next->post_date, 0, strpos( $next->post_date, ' ' ) ) )
				break;

			// Looks good -- set up the post and then output this snippet
			$post = $next;
			setup_postdata( $post );

			// If this was the first post output for this format, include a collapsible wrapper
			if ( $first ) {
				echo '<div class="collapse" data-date="' . esc_attr( get_the_date() ) . '">';
				$first = false;
			}
			get_template_part( 'snippet', $this_post_format );
		}
		if ( $next ) { // If there was a $next, but it wasn't an Aside
			if ( !$first )
				echo '</div>'; // collapse wrapper
			homeroom_prev_post(); // dial it back a notch
			$post = $temp_post;
		}
	}

	?>
</article>
