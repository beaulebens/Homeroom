<?php
if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'homeroom' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php homeroom_tags_list(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		if ( is_front_page() ) {
			// On the homepage, show the featured image as a small square, off to the right
			if ( has_post_thumbnail() ) {
				echo '<a href="' . get_permalink() . '" class="featured-image">';
				the_post_thumbnail( 'thumbnail' );
				echo '</a>';
			}
			the_excerpt( __( 'Read full post <span class="meta-nav">&rarr;</span>', 'homeroom' ) );
		} else {
			// On a single page, show the full featured image
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'post-thumbnail' );
			}
			the_content();
		}
		?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'homeroom' ), 'after' => '</div>' ) ); ?>

		<?php get_template_part( 'map', 'singlepoint' ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php homeroom_permalink_datestamp( false, 'icon-link permalink' ); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<div class="clearfix"></div>
</div>