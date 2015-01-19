<?php

/**
 * Plugin Name: CodeArt Meta Tags
 * Plugin URI: https://github.com/codeartmk/codeart-meta-tags/
 * Description: Add meta tags such as open graph, viewport, author and etc...
 * Version: 1.0.0
 * Author: CodeArt Team
 * Author URI: http://codeart.mk/
 * License: A short license name. Example: GPL2
 */


/*  Copyright 2015  CodeArt Team  (email : contact[at]codeart[dot]]mk)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* Define constants */
define( 'CODEART_META_TAGS_DEFAULT_IMAGE', plugin_dir_url( __FILE__ ) . 'og-logo.jpg' );
define( 'CODEART_META_TAGS_DESCRIPTION_LENGTH', 80 );



/**
 *	Hook CodeArt Meta Tags on all front-end pages
 **/
if( !is_admin() )
{
	add_action( 'wp_head', 'codeart_add_meta_tags', 5 );
	add_filter( 'language_attributes' , 'codeart_add_opengraph_doctype' );
}



/**
 *	Adding the Open Graph in the Language Attributes
 **/
function codeart_add_opengraph_doctype( $output )
{
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}



/**
 *	Method to generate OG META Tags
 **/
function codeart_add_meta_tags()
{
	$og_title 		= get_bloginfo( 'name' );
	$og_url 		= get_bloginfo( 'url' );
	$og_image 		= CODEART_META_TAGS_DEFAULT_IMAGE;
	$og_site_name 	= get_bloginfo( 'name' );
	$og_description = get_bloginfo( 'description' );

	if( is_home() || is_front_page() )
	{
		$og_title 		= get_bloginfo( 'name' );
		$og_url 		= get_bloginfo( 'url' );
		$og_image 		= CODEART_META_TAGS_DEFAULT_IMAGE;
		$og_site_name 	= get_bloginfo( 'name' );
		$og_description = get_bloginfo( 'description' );
	}
	else
	{
		if( is_singular() || is_page() )
		{
			global $post;
			$og_title 		= get_the_title( $post->ID );
			$og_url 		= get_permalink( $post->ID );

			if( has_post_thumbnail( $post->ID ) ):
				$og_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				$og_image = $og_image[0];
			endif;

			if( $post->post_content )
				$og_description = substr(
					strip_tags(
						apply_filters( 'the_content', $post->post_content )
					), 0, CODEART_META_TAGS_DESCRIPTION_LENGTH );
		}
		else
		{
			if( is_category() )
			{
				$category_id = intval( get_query_var('cat') );
				$og_title = get_category( $category_id )->name;

				$category_link = get_category_link( $category_id );
				if( $category_link )
					$og_url = $category_link;

				$category_description =
				substr(
					strip_tags(
						category_description( $category_id )
					),
				0, CODEART_META_TAGS_DESCRIPTION_LENGTH ) . '...';

				if( $category_description )
					$og_description = $category_description;
			}
		}
	}
	?>
	<meta property="og:title" content="<?php echo $og_title; ?>" />
    <meta property="og:url" content="<?php echo $og_url; ?>" />
    <meta property="og:image" content="<?php echo $og_image; ?>" />
    <meta property="og:site_name" content="<?php echo $og_site_name; ?>" />
    <meta property="og:description" content="<?php echo $og_description; ?>" />
	<?php
}

?>