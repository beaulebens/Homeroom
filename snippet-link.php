<?php
if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="hr"></div>
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
		<div class="post-format-link-url"><a href="<?php echo esc_url( $url ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo esc_html( $url ); ?></a></div>
		<?php homeroom_tags_list(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
		$icon = $service = false;
		$keyring_service = wp_get_object_terms( get_the_ID(), 'keyring_services' );
		if ( $keyring_service )
			$keyring_service = $keyring_service[0]->name;
		if ( 'Delicious' == $keyring_service ) {
			if ( ! $deliciouser = get_user_meta( $post->post_author, 'delicious', true ) )
				$deliciouser = '';
			$icon = 'icon-delicious';
			$service = '<a href="http://delicious.com/' . esc_attr( $deliciouser ) . '" rel="nofollow">Delicious</a>';
		} else if ( 'Instapaper' == $keyring_service ) {
			$icon = 'icon-file';
			$service = '<a href="http://instapaper.com" rel="nofollow">Instapaper</a>';
		}
		if ( $icon && $service ) {
			echo '<span class="post-source ' . esc_attr( $icon ) . '">' . sprintf( esc_html( __( 'Saved on %s', 'homeroom' ) ), $service ) . '</span>';
		}
		?>
		<?php homeroom_permalink_datestamp( false, 'icon-link permalink' ); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<div class="clearfix"></div>
</div>