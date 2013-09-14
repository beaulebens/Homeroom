<?php

// Default, for Standard Posts

$icon_size = 40;

$obj = get_queried_object();

// Use the author's Gravatar by default
$gravatar = get_avatar( $obj->post_author, $icon_size );
$gravatar = str_replace( "class='", "class='format-icon ", $gravatar );
echo '<a href="' . get_author_posts_url( $obj->post_author ) . '">';
echo $gravatar;
echo '</a>';
