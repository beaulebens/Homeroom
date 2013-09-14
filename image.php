<?php
/**
 * The template for displaying image attachments.
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

get_header();
?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
				<div id="timeline">

					<?php
					if ( post_password_required() ) {
						echo get_the_password_form();
						return;
					}
					?>

					<article class="f-<?php echo esc_attr( get_post_format() ); ?>">
						<?php get_template_part( 'once', 'attachment' ); ?>

						<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<h1 class="entry-title">
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'homeroom' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
								</h1>
								<?php homeroom_tags_list(); ?>
							</header><!-- .entry-header -->

							<div class="entry-content">
								<div class="entry-attachment">
									<div class="attachment">
										<?php
											/**
											 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
											 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
											 */
											$attachments = array_values(
												get_children(
													array(
														'post_parent'    => $post->post_parent,
														'post_status'    => 'inherit',
														'post_type'      => 'attachment',
														'post_mime_type' => 'image',
														'order'          => 'ASC',
														'orderby'        => 'menu_order ID'
													)
												)
											);
											foreach ( $attachments as $k => $attachment ) {
												if ( $attachment->ID == $post->ID )
													break;
											}
											$k++;
											// If there is more than 1 attachment in a gallery
											if ( count( $attachments ) > 1 ) {
												if ( isset( $attachments[ $k ] ) )
													// get the URL of the next image attachment
													$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
												else
													// or get the URL of the first image attachment
													$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
											} else {
												// or, if there's only 1 image, get the URL of the image
												$next_attachment_url = wp_get_attachment_url();
											}
										?>

										<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
											$attachment_size = apply_filters( 'homeroom_attachment_size', array( 1200, 1200 ) ); // Filterable image size.
											echo wp_get_attachment_image( $post->ID, $attachment_size );
										?></a>
									</div><!-- .attachment -->

									<?php if ( ! empty( $post->post_excerpt ) ) : ?>
									<div class="entry-caption">
										<?php the_excerpt(); ?>
									</div><!-- .entry-caption -->
									<?php endif; ?>
								</div><!-- .entry-attachment -->

								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'homeroom' ), 'after' => '</div>' ) ); ?>

								<?php get_template_part( 'map', 'singlepoint' ); ?>
							</div><!-- .entry-content -->

							<footer class="entry-meta">
								<?php homeroom_permalink_datestamp( false, 'icon-calendar permalink' ); ?>
								<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
							</footer><!-- .entry-meta -->

							<div class="clearfix"></div>
						</div>
					</article>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template( '', true );
					?>

				</div><!-- #timeline -->
			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>