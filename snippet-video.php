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
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'homeroom' ), 'after' => '</div>' ) ); ?>

		<?php get_template_part( 'map', 'singlepoint' ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php homeroom_tags_list(); ?>
		<?php
		$keyring_service = wp_get_object_terms( get_the_ID(), 'keyring_services' );
		if ( $keyring_service ) {
			$keyring_service = $keyring_service[0]->name;
		}
		$icon = $service = false;
		if ( 'Instagram' == $keyring_service ) {
			$icon = 'icon-instagram';
			$url = get_post_meta( get_the_ID(), 'instagram_url', true );
			$url = $url ? $url : 'http://instagram.com';
			$service = '<a href="' . esc_url( $url ) . '" rel="nofollow">Instagram</a>';
		} else if ( 'Flickr' == $keyring_service ) {
			$icon = 'icon-flickr';
			$url = get_post_meta( get_the_ID(), 'flickr_url', true );
			$url = $url ? $url : 'http://flickr.com';
			$service = '<a href="' . esc_url( $url ) . '" rel="nofollow">Flickr</a>';
		}
		if ( $icon && $service ) {
			echo '<span class="post-source ' . esc_attr( $icon ) . '">' . sprintf( esc_html( __( 'Posted on %s', 'homeroom' ) ), $service ) . '</span>';
		}
		?>
		<?php homeroom_permalink_datestamp( false, 'icon-link permalink' ); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->

	<div class="clearfix"></div>
</div>
