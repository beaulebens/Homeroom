
<?php if ( $tag = get_post_custom_values( 'related_posts_tag' ) ) : ?>
	<?php $headlines = get_posts( array( 'tag' => $tag[0] ) ) ?>
	<?php if ( count( $headlines ) ) : ?>
		<div class="related-posts-container">
			<h2><?php _e( 'Related Posts', 'homeroom' ); ?></h2>
			<ul class="related-posts">
			<?php foreach ( $headlines as $post ) : setup_postdata( $post ); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <small>(<?php the_date(); ?>)</small></li>
			<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
<?php endif; ?>