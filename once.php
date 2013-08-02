<?php

// Default, for Standard Posts

$icon_size = 40;

// Use the author's Gravatar by default
$gravatar = get_avatar( get_the_author_meta( 'ID' ), $icon_size );
$gravatar = str_replace( "class='", "class='format-icon ", $gravatar );
echo '<a href="' . get_author_posts_url( false, get_the_author_meta( 'ID' ) ) . '">';
echo $gravatar;
echo '</a>';
