<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php $header_image = get_header_image();
	if ( ! empty( $header_image ) ) { ?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
			<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
		</a>
	<?php } // if ( ! empty( $header_image ) ) ?>

 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @uses homeroom_header_style()
 * @uses homeroom_admin_header_style()
 * @uses homeroom_admin_header_image()
 *
 * @package Homeroom
 */
function homeroom_custom_header_setup() {
	$args = array(
		'default-image'          => '',
		'default-text-color'     => '000',
		'width'                  => 1000,
		'height'                 => 250,
		'flex-height'            => true,
		'wp-head-callback'       => 'homeroom_header_style',
		'admin-head-callback'    => 'homeroom_admin_header_style',
		'admin-preview-callback' => 'homeroom_admin_header_image',
	);

	$args = apply_filters( 'homeroom_custom_header_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-header', $args );
	} else {
		// Compat: Versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR',    $args['default-text-color'] );
		define( 'HEADER_IMAGE',        $args['default-image'] );
		define( 'HEADER_IMAGE_WIDTH',  $args['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $args['height'] );
		add_custom_image_header( $args['wp-head-callback'], $args['admin-head-callback'], $args['admin-preview-callback'] );
	}
}
add_action( 'after_setup_theme', 'homeroom_custom_header_setup' );

/**
 * Shiv for get_custom_header().
 *
 * get_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @return stdClass All properties represent attributes of the curent header image.
 *
 * @package Homeroom
 * @since Homeroom 1.1
 */

if ( ! function_exists( 'get_custom_header' ) ) {
	function get_custom_header() {
		return (object) array(
			'url'           => get_header_image(),
			'thumbnail_url' => get_header_image(),
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
		);
	}
}

if ( ! function_exists( 'homeroom_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see homeroom_custom_header_setup().
 *
 * @since Homeroom 1.0
 */
function homeroom_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.site-title,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // homeroom_header_style

if ( ! function_exists( 'homeroom_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see homeroom_custom_header_setup().
 *
 * @since Homeroom 1.0
 */
function homeroom_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
	}
	#headimg h1 {
	}
	#headimg h1 a {
	}
	#desc {
	}
	#headimg img {
	}
	</style>
<?php
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
function homeroom_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // homeroom_admin_header_image

/**
 * We want users to also be able to upload an image which will be cropped to a square.
 * In that case, it will be displayed to the left, on the timeline, with their text to
 * the right.
 *
 * Unfortunately the core header pages are not very hookable, so we have to hack this with
 * JS.
 */
function homeroom_allow_square_cropping() {
	global $pagenow;
	if ( 'themes.php' != $pagenow )
		return;

	if ( empty( $_REQUEST['page'] ) || 'custom-header' != $_REQUEST['page'] )
		return;

	if ( empty( $_REQUEST['step'] ) || '2' != $_REQUEST['step'] )
		return;

	?><script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#crop_image').before( '<p><?php echo esc_js( __( 'To upload a smaller (square) image and place it over the timeline in the top-left of your site, click this button:', 'homeroom' ) ); ?> <input type="button" name="force_square" id="force_square" value="<?php echo esc_attr( __( 'Crop to a square', 'homeroom' ) ); ?>" class="button" /></p>' );
		jQuery('#force_square').on( 'click', function() {

		});
		ias = jQuery('img#upload').imgAreaSelect();
		ias.update();
	});
	</script><?php
}
add_action( 'admin_footer', 'homeroom_allow_square_cropping' );

