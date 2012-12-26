<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( $twitter_id = get_post_meta( get_the_ID(), 'twitter_id', true ) ) : ?>
		<blockquote class="twitter-tweet">
			<?php
			the_content();

			if ( ! $permalink = get_post_meta( get_the_ID(), 'twitter_permalink', true ) )
				$permalink = "https://twitter.com/" . get_user_meta( $post->post_author, 'twitter' ) . "/status/{$twitter_id}";

			homeroom_permalink_datestamp( $permalink, 'twitter-permalink' );
			?>
		</blockquote>
		<?php homeroom_tags_list(); ?>
		<footer class="entry-meta">
			<?php homeroom_permalink_datestamp(); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
		<div class="clearfix"></div>
	<?php else : ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php homeroom_permalink_datestamp(); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	<?php endif; ?>

	<?php get_template_part( 'in-page-map' ); ?>

</div>