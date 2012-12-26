<?php

if ( !is_home() || !is_user_logged_in() || !current_user_can( 'publish_posts' ) || get_query_var( 'paged' ) )
	return;

?>
<div id="open-editor" class="shadow"></div>

<article id="editor" class="shadow">
	<ul>
		<li class="editor-title">
			<input type="text" name="editor-title" id="editor-title" value="" placeholder="<?php esc_html_e( 'Enter title here', 'homeroom' ); ?>" />
		</li>
		<li class="editor-content">
			<textarea name="editor-content" id="editor-content" placeholder="<?php esc_html_e( 'Write your post here...', 'homeroom' ); ?>"></textarea>
		</li>
		<li class="editor-buttons">
			<input type="text" name="editor-tags" id="editor-tags" value="" placeholder="<?php esc_html_e( 'comma, separated, tags', 'homeroom' ); ?>" />
			<?php homeroom_restrict_manage_posts( false, __( 'Post', 'homeroom' ) ); ?>
			<input type="submit" name="editor-submit-draft" id="editor-submit-draft" value="Save Draft" />
			<input type="submit" name="editor-submit-publish" id="editor-submit-publish" value="Publish" class="button-primary" />
		</li>
	</ul>
</article>

<script type="text/javascript">
(function($){
	var editorOpen = false;
	$( '#open-editor' ).append( '<a>+</a>' ).css( 'cursor', 'pointer' ).on( 'click', function(){

		// @todo: Rotate + to x (3 full spins, with easing)

		if ( editorOpen ) {
			// Closing Editor
			// fadeOut editor internals
			$( '#editor *' ).fadeOut( function(){
				// Shrink down the dimensions
				$( '#editor' ).append( '<div id="remove-after-resize">&nbsp;</div>' ).animate( { width: '0px', height: '0px', opacity: 0 }, function(){
					// Remove resize holder
					$( '#remove-after-resize' ).remove();
					editorOpen = false;
					$( '#open-editor a' ).text( '+' );
				});

			});
		} else {
			// Opening Editor
			// Hide Editor internals
			$( '#editor *' ).hide();

			// Expand out the width of the editor, then slide down the height and fadeIn the internals
			$( '#editor' ).append( '<div id="remove-after-resize">&nbsp;</div>' ).css( { width: '0px', height: '0px', display: 'block', opacity: 0 } ).animate( { width: '87.5%', height: '200px', opacity: 1 }, function(){
				// Show internals once resized
				$( '#editor *' ).fadeIn();
				$( '#remove-after-resize' ).remove();
				$( '#editor-title' ).focus();
				$( '#open-editor a' ).text( '×' );
				editorOpen = true;
			});
		}
	});

	// Tag auto-completion
	$(document).ready(function(){
		$( '#editor-tags' ).suggest( ajaxurl + '?action=ajax-tag-search&tax=post_tag', { delay: 500, minchars: 2, multiple: true, resultsClass: 'editor-tag-suggest', selectClass: 'editor-tag-suggestion' } );
	});
})(jQuery);
</script>