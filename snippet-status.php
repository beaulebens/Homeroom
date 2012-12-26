<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$GLOBALS['homeroom_map_markers'] = array( $post );
	if ( is_front_page() )
		echo '<article class="shadow f-status">';
	get_template_part( 'multimap' );
	if ( is_front_page() )
		echo '</article>';
	unset( $GLOBALS['homeroom_map_markers'] );
	?>
	<footer class="entry-meta">
		<?php homeroom_permalink_datestamp(); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</div>