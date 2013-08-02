<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="hr"></div>
	<?php if ( $twitter_id = get_post_meta( get_the_ID(), 'twitter_id', true ) ) : ?>
		<blockquote<?php if ( Homeroom::get_option( 'enable_twitter_embeds' ) ) : ?> class="twitter-tweet"<?php endif; ?>>
			<?php
			the_content();

			if ( ! $permalink = get_post_meta( get_the_ID(), 'twitter_permalink', true ) ) {
				if ( $twitterer = get_user_meta( $post->post_author, 'twitter' ) ) {
					$permalink = "https://twitter.com/" . esc_attr( $twitterer ) . "/statuses/{$twitter_id}";
				} else {
					$permalink = get_permalink();
				}
			}
			?>
			<a href="<?php echo esc_url( $permalink ); ?>" rel="nofollow" style="display:none;">Twitter</a>
		</blockquote>
		<footer class="entry-meta">
			<?php homeroom_tags_list(); ?>
			<span class="post-source icon-twitter"><?php echo sprintf( esc_html( __( 'Posted on %s', 'homeroom' ) ), '<a href="' . esc_url( $permalink ) . '" rel="nofollow">Twitter</a>' ); ?></span>
			<?php homeroom_permalink_datestamp( false, 'icon-calendar permalink' ); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
			<?php get_template_part( 'map', 'singlepoint' ); ?>
		</footer><!-- .entry-meta -->
		<div class="clearfix"></div>
	<?php else : ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php homeroom_tags_list(); ?>
			<?php homeroom_permalink_datestamp( false, 'icon-calendar permalink' ); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	<?php endif; ?>

</div>