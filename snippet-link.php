<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	// Find a good URL - Keyring importers store one in postmeta to make this easier
	if ( !$url = get_post_meta( get_the_ID(), 'href', true ) ) {
		// No such luck -- parse the_content for the first one we can find
		$content = get_the_content();
		preg_match_all( '#<a.*href=[\'"]([^\'"]+)[\'"]#sUi', $content, $hrefs );
		if ( count( $hrefs[1] ) ) {
			foreach ( $hrefs[1] as $href ) {
				// At least make sure it looks like an external URL
				if ( 'http' == substr( $href, 0, 4 ) ) {
					$url = $href;
					break;
				}
			}
		} else {
			$url = get_permalink( get_the_ID() );
		}
	}
	?>
	<header class="entry-header">
		<h1 class="entry-title">
			<a href="<?php echo esc_url( $url ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php homeroom_tags_list(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php homeroom_permalink_datestamp(); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<div class="clearfix"></div>
</div>