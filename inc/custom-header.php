<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support different elements of the
 * header. We don't support <3.4
 *
 * @package Homeroom
 */
function homeroom_custom_header_setup() {
	$args = array(
		'default-image'          => '',
		'default-text-color'     => 'fff',
		'width'                  => 1000,
		'height'                 => 200,
		'flex-height'            => true,
		'wp-head-callback'       => 'homeroom_admin_header_style',
		'admin-head-callback'    => 'homeroom_admin_header_style',
		'admin-preview-callback' => 'homeroom_admin_header_image',
	);

	$args = apply_filters( 'homeroom_custom_header_args', $args );

	add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'homeroom_custom_header_setup' );

if ( ! function_exists( 'homeroom_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see homeroom_custom_header_setup().
 *
 * @since Homeroom 1.0
 */
function homeroom_admin_header_style() {
	$header_image = get_header_image();
	?>
	<style type="text/css">
		<?php if ( display_header_text() ) : ?>
			/* Custom Header Text */
			#masthead h1 a,
			#masthead h1 a:hover,
			#masthead h2 {
			<?php if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() ) : ?>
				display: none;
			<?php else : ?>
				color: #<?php echo esc_attr( get_header_textcolor() ); ?>;
				text-decoration: none;
				border: 0;
				background: none;
				color: #222;
			<?php endif; ?>
			}
			#masthead h1 {
				padding: 10px;
				margin: 15px 0 10px 15px;
				font-size: 35px;
				font-family: "Constantia","PT Serif","Georgia","Helvetica Neue",Arial,sans-serif;
				line-height: 1em;
				background: #000;
				opacity: 0.7;
				float: left;
			}
			#masthead h2 {
				padding: 10px;
				margin: 0 15px;
				font-size: 25px;
				font-family: "Constantia","PT Serif","Georgia","Helvetica Neue",Arial,sans-serif;
				line-height: 1em;
				background: #000;
				opacity: 0.7;
				display: block;
				width: auto;
				text-shadow: none;
				float: left;
				clear: left;
			}
		<?php endif; ?>

		<?php if ( empty( $header_image ) ) : ?>
			/* NO Header Image */
			#masthead h1 {
				margin: 1em 0 10px 0;
				padding: 0;
				background: none;
			}
			#masthead h1 a {
				color: #111;
			}
			#masthead h2 {
				margin: 0 0 1em 0;
				padding: 0;
				color: #111;
				background: none;
			}
		<?php else :
			$header_image_data = get_custom_header();
			?>
			/* Custom Header Image */
			#masthead {
				position: relative;
			}

			#masthead hgroup {
				height: <?php echo esc_attr( $header_image_data->height ); ?>px;
			}

			#masthead img {
				position: absolute;
				top: 0px;
				left: 0px;
				z-index: -1;
			}
			#masthead h1 a,
			#masthead h1 a:hover,
			#masthead h2 {
				color: #fdfdfa;
			}
		<?php endif; ?>
	</style><?php
}
endif; // homeroom_admin_header_style

if ( ! function_exists( 'homeroom_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see homeroom_custom_header_setup().
 *
 * @since Homeroom 1.0
 */
function homeroom_admin_header_image() {
	homeroom_admin_header_style();
	?>
	<header id="masthead" class="site-header" role="banner">
		<hgroup>
			<?php if ( display_header_text() ) : ?>
				<h1 class="site-title"><a onclick="return false;" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			<?php endif; ?>
			<?php $header_image = get_header_image();
				if ( ! empty( $header_image ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
					</a>
			<?php endif; ?>
		</hgroup>
	</header><!-- #masthead .site-header -->
<?php }
endif; // homeroom_admin_header_image
