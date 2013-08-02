<?php

if ( !Homeroom::get_option( 'enable_frontend_postbox' ) || !is_home() || !is_user_logged_in() || !current_user_can( 'publish_posts' ) || get_query_var( 'paged' ) )
	return;

require_once get_template_directory() . '/inc/admin.php';

?>
<div id="open-editor"></div>

<article id="editor">
	<?php
	$gravatar = get_avatar( get_current_user_id(), 40 );
	$gravatar = str_replace( "class='", "class='format-icon ", $gravatar );
	echo $gravatar;
	?>
	<ul>
		<li class="editor-title">
			<input type="text" name="editor-title" id="editor-title" value="" placeholder="<?php esc_html_e( 'Enter title here', 'homeroom' ); ?>" />
		</li>
		<li class="editor-content">
			<textarea name="editor-content" id="editor-content" placeholder="<?php esc_html_e( 'Write your post here...', 'homeroom' ); ?>"></textarea>
		</li>
		<li class="editor-buttons">
			<input type="text" name="editor-tags" id="editor-tags" value="" placeholder="<?php esc_html_e( 'comma, separated, tags', 'homeroom' ); ?>" />
			<?php homeroom_post_formats_select( false, __( 'Post', 'homeroom' ) ); ?>
			<input type="submit" name="editor-submit-draft" id="editor-submit-draft" value="Save Draft" />
			<input type="submit" name="editor-submit-publish" id="editor-submit-publish" value="Publish" class="button-primary" />
		</li>
	</ul>
</article>

<script type="text/javascript">
(function($){
	var editorOpen = false;
	$( '#editor' ).hide();
	$( '#open-editor' ).append( '<a>+</a>' ).css( 'cursor', 'pointer' ).on( 'click', function(e){
		e.preventDefault();
		if ( editorOpen ) {
			// Closing Editor
			// Shrink down the dimensions
			$( '#editor' ).animate( { width: '0px', height: '0px', opacity: 0 }, function(){
				$( '#editor' ).hide();
				$( '#open-editor a' ).text( '+' );
			});
		} else {
			// Opening Editor
			// Expand out the width of the editor, then slide down the height and fadeIn the internals
			$( '#editor' ).css( { width: '0px', height: '0px', display: 'block', opacity: 0 } ).animate( { width: '84%', height: '200px', opacity: 1 }, function(){
				// Show internals once resized
				$( '#editor-title' ).focus();
				$( '#open-editor a' ).text( '×' );
			});
		}

		editorOpen = !editorOpen;

		return false;
	});

	// Tag auto-completion
	$(document).ready(function(){
		$( '#editor-tags' ).suggest( ajaxurl + '?action=ajax-tag-search&tax=post_tag', { delay: 500, minchars: 2, multiple: true, resultsClass: 'editor-tag-suggest', selectClass: 'editor-tag-suggestion' } );
	});
})(jQuery);
</script>