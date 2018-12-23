<?php
/**
 * Add post shortcodes.
 *
 * @package JupiterX\Framework\Templates\Fragments
 *
 * @since   1.0.0
 */

jupiterx_add_smart_action( 'jupiterx_post_meta_date', 'jupiterx_post_meta_date_shortcode' );
/**
 * Echo post meta date shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta_date_shortcode() {
	jupiterx_open_markup_e( 'jupiterx_post_meta_date_prefix', 'span' );

		jupiterx_output_e( 'jupiterx_post_meta_date_prefix_text', __( 'Posted on ', 'jupiterx' ) );

	jupiterx_close_markup_e( 'jupiterx_post_meta_date_prefix', 'span' );

	jupiterx_open_markup_e(
		'jupiterx_post_meta_date',
		'time',
		array(
			'datetime' => get_the_time( 'c' ),
			'itemprop' => 'datePublished',
		)
	);

		jupiterx_output_e( 'jupiterx_post_meta_date_text', get_the_time( get_option( 'date_format' ) ) );

	jupiterx_close_markup_e( 'jupiterx_post_meta_date', 'time' );
}

jupiterx_add_smart_action( 'jupiterx_post_meta_author', 'jupiterx_post_meta_author_shortcode' );
/**
 * Echo post meta author shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta_author_shortcode() {
	jupiterx_open_markup_e( 'jupiterx_post_meta_author_prefix', 'span' );

		jupiterx_output_e( 'jupiterx_post_meta_author_prefix_text', __( 'By ', 'jupiterx' ) );

	jupiterx_close_markup_e( 'jupiterx_post_meta_author_prefix', 'span' );

	jupiterx_open_markup_e(
		'jupiterx_post_meta_author',
		'a',
		array(
			'href'      => get_author_posts_url( get_the_author_meta( 'ID' ) ), // Automatically escaped.
			'rel'       => 'author',
			'itemprop'  => 'author',
			'itemscope' => '',
			'itemtype'  => 'http://schema.org/Person',
		)
	);

	$author = get_the_author_meta( 'nickname', get_post_field( 'post_author', get_the_ID() ) );

		jupiterx_output_e( 'jupiterx_post_meta_author_text', $author );

		jupiterx_selfclose_markup_e(
			'jupiterx_post_meta_author_name_meta',
			'meta',
			array(
				'itemprop' => 'name',
				'content'  => $author,
			)
		);

	jupiterx_close_markup_e( 'jupiterx_post_meta_author', 'a' );
}

jupiterx_add_smart_action( 'jupiterx_post_meta_author_avatar', 'jupiterx_post_meta_author_avatar_shortcode' );
/**
 * Echo post meta author avatar shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta_author_avatar_shortcode() {
	$content = get_avatar( get_the_author_meta( 'ID' ), 50 );

	if ( ! $content ) {
		return;
	}

	echo $content; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Echoes get_avatar().
}

jupiterx_add_smart_action( 'jupiterx_post_meta_comments', 'jupiterx_post_meta_comments_shortcode' );
/**
 * Echo post meta comments shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 * @SuppressWarnings(PHPMD.ElseExpression)
 */
function jupiterx_post_meta_comments_shortcode() {
	global $post;

	if ( post_password_required() || ! comments_open() ) {
		return;
	}

	$comments_number = (int) get_comments_number( $post->ID );

	if ( $comments_number < 1 ) {
		$comment_text = jupiterx_output( 'jupiterx_post_meta_empty_comment_text', __( 'Leave a comment', 'jupiterx' ) );
	} elseif ( 1 === $comments_number ) {
		$comment_text = jupiterx_output( 'jupiterx_post_meta_comments_text_singular', __( '1 comment', 'jupiterx' ) );
	} else {
		$comment_text = jupiterx_output(
			'jupiterx_post_meta_comments_text_plural',
			// translators: Number of comments. Plural.
			__( '%s comments', 'jupiterx' )
		);
	}

	$attrs = [
		'href'                        => get_comments_link(),
		'data-jupiterx-scroll-target' => '#respond',
	];

	jupiterx_open_markup_e( 'jupiterx_post_meta_comments', 'a', $attrs ); // Automatically escaped.

		printf( $comment_text, (int) get_comments_number( $post->ID ) ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- Pending security audit.

	jupiterx_close_markup_e( 'jupiterx_post_meta_comments', 'a' );
}

jupiterx_add_smart_action( 'jupiterx_post_meta_tags', 'jupiterx_post_meta_tags_shortcode' );
/**
 * Echo post meta tags shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta_tags_shortcode() {
	$args = [
		'post_type'  => get_post_type(),
		'taxonomy'   => 'post_tag',
		'links'      => [],
		'separator'  => ' ',
	];

	if ( 'portfolio' === $args['post_type'] ) {
		$args['taxonomy'] = 'portfolio_tag';
	}

	/**
	 * Filter post meta tags arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The post meta tags arguments.
	 */
	$args = apply_filters( 'jupiterx_post_meta_tags_args', $args );

	$terms = get_the_tags( get_the_ID(), $args['taxonomy'] );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		$term_link = get_tag_link( $term );

		if ( is_wp_error( $term_link ) ) {
			continue;
		}

		$args['links'][] = '<a class="btn btn-light" href="' . esc_url( $term_link ) . '" rel="tag">' . $term->name . '</a>';
	}

	printf(
		'%1$s%2$s',
		jupiterx_output( 'jupiterx_post_meta_tags_prefix', __( ' ', 'jupiterx' ) ), // @codingStandardsIgnoreLine
		join( $args['separator'], $args['links'] ) // @codingStandardsIgnoreLine
	);
}

jupiterx_add_smart_action( 'jupiterx_post_meta_categories', 'jupiterx_post_meta_categories_shortcode' );
/**
 * Echo post meta categories shortcode.
 *
 * @since 1.0.0
 *
 * @return void
 */
function jupiterx_post_meta_categories_shortcode() {
	$args = [
		'post_type'  => get_post_type(),
		'taxonomy'   => 'category',
		'links'      => [],
		'separator'  => ', ',
	];

	if ( 'portfolio' === $args['post_type'] ) {
		$args['taxonomy'] = 'portfolio_category';
	}

	/**
	 * Filter post meta categories arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The post meta categories arguments.
	 */
	$args = apply_filters( 'jupiterx_post_meta_categories_args', $args );

	$terms = get_the_terms( get_the_ID(), $args['taxonomy'] );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return;
	}

	foreach ( $terms as $term ) {
		$term_link = get_term_link( $term );

		if ( is_wp_error( $term_link ) ) {
			continue;
		}

		$args['links'][] = '<a href="' . esc_url( $term_link ) . '" rel="category">' . $term->name . '</a>';
	}

	$prefix = jupiterx_open_markup( 'jupiterx_post_meta_categories_prefix', 'span' );

	$prefix .= jupiterx_output( 'jupiterx_post_meta_categories_prefix_text', __( 'In ', 'jupiterx' ) ); // @codingStandardsIgnoreLine

	$prefix .= jupiterx_close_markup( 'jupiterx_post_meta_categories_prefix', 'span' );

	printf(
		'%1$s%2$s',
		$prefix, // @codingStandardsIgnoreLine
		join( $args['separator'], $args['links'] ) // @codingStandardsIgnoreLine
	);
}

/**
 * Echo social share shortcode.
 *
 * @since 1.0.0
 *
 * @param string  $markup_key Markup ID.
 * @param array   $filtered_social Filter social networks to show.
 * @param boolean $name_enabled The name enabled.
 *
 * @return void
 */
function jupiterx_post_social_share_shortcode( $markup_key, $filtered_social = [], $name_enabled = true ) {
	$filtered_social = array_filter( $filtered_social ); // Fix for array with empty string value when no icon is selected.

	if ( empty( $filtered_social ) ) {
		return;
	}

	$page_title = get_the_title();
	$page_link  = get_permalink();

	$social_networks = [
		'facebook'    => [
			'label'  => __( 'Share on Facebook', 'jupiterx' ),
			'name'   => __( 'Facebook', 'jupiterx' ),
			'url'    => 'https://facebook.com/sharer/sharer.php?u=%1$s',
			'icon'   => 'facebook-f',
		],
		'twitter'     => [
			'label' => __( 'Share on Twitter', 'jupiterx' ),
			'name'  => __( 'Twitter', 'jupiterx' ),
			'url'   => 'https://twitter.com/intent/tweet/?text=%2$s&url=%1$s',
			'icon'  => 'twitter',
		],
		'pinterest'   => [
			'label' => __( 'Share on Pinterest', 'jupiterx' ),
			'name'  => __( 'Pinterest', 'jupiterx' ),
			'url'   => 'https://pinterest.com/pin/create/button/?url=%1$s&media=%1$s&description=%2$s',
			'icon'  => 'pinterest-p',
		],
		'linkedin'    => [
			'label' => __( 'Share on LinkedIn', 'jupiterx' ),
			'name'  => __( 'LinkedIn', 'jupiterx' ),
			'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=%1$s&title=%2$s&summary=%2$s&source=%1$s',
			'icon'  => 'linkedin-in',
		],
		'google-plus' => [
			'label' => __( 'Share on Google+', 'jupiterx' ),
			'name'  => __( 'Google+', 'jupiterx' ),
			'url'   => 'https://plus.google.com/share?url=%1$s',
			'icon'  => 'google-plus',
		],
		'reddit'      => [
			'label' => __( 'Share on Reddit', 'jupiterx' ),
			'name'  => __( 'Reddit', 'jupiterx' ),
			'url'   => 'https://reddit.com/submit/?url=%1$s',
			'icon'  => 'reddit-alien',
		],
		'digg'        => [
			'label' => __( 'Share on Digg', 'jupiterx' ),
			'name'  => __( 'Digg', 'jupiterx' ),
			'url'   => 'https://digg.com/submit?url=%1$s',
			'icon'  => 'digg',
		],
		'email'       => [
			'label'  => __( 'Share on Email', 'jupiterx' ),
			'name'   => __( 'Email', 'jupiterx' ),
			'url'    => 'mailto:?subject=%2$s&body=%1$s',
			'icon'   => 'share-email',
			'target' => '_self',
		],
	];

	$wrapper_attrs = [
		'class' => 'jupiterx-social-share jupiterx-social-share-' . str_replace( '_', '-', $markup_key ),
	];

	jupiterx_open_markup_e( sprintf( 'jupiterx_social_share[%1$s]', $markup_key ), 'div', $wrapper_attrs );

		jupiterx_open_markup_e( sprintf( 'jupiterx_social_share_inner[%1$s]', $markup_key ), 'div', 'class=jupiterx-social-share-inner' );

			foreach ( $filtered_social as $social_key ) { // phpcs:ignore

				if ( isset( $social_networks[ $social_key ] ) ) {  // phpcs:ignore

					$social_attrs = $social_networks[ $social_key ];

					$attrs = [
						'class'      => 'jupiterx-social-share-link btn jupiterx-social-share-' . $social_key,
						'href'       => esc_url( sprintf( $social_attrs['url'], $page_link, $page_title ) ),
						'target'     => isset( $social_attrs['target'] ) ? $social_attrs['target'] : '_blank',
						'aria-label' => $social_attrs['label'],
					];

					jupiterx_open_markup_e( sprintf( 'jupiterx_social_share_link[%1$s][%2$s]', $markup_key, $social_key ), 'a', $attrs );

						jupiterx_open_markup_e( sprintf( 'jupiterx_social_share_link_icon[%1$s][%2$s]', $markup_key, $social_key ), 'span', 'class=jupiterx-icon jupiterx-icon-' . $social_attrs['icon'] );

						jupiterx_close_markup_e( sprintf( 'jupiterx_social_share_link_icon[%1$s][%2$s]', $markup_key, $social_key ), 'span' );

						if ( $name_enabled ) { // phpcs:ignore

							jupiterx_open_markup_e( sprintf( 'jupiterx_social_share_link_name[%1$s][%2$s]', $markup_key, $social_key ), 'span', 'class=jupiterx-social-share-link-name' );

								jupiterx_output_e( sprintf( 'jupiterx_social_share_link_name_text[%1$s][%2$s]', $markup_key, $social_key ), $social_attrs['name'] );

							jupiterx_close_markup_e( sprintf( 'jupiterx_social_share_link_name[%1$s][%2$s]', $markup_key, $social_key ), 'span' );

						} // phpcs:ignore

					jupiterx_close_markup_e( sprintf( 'jupiterx_social_share_link[%1$s][%2$s]', $markup_key, $social_key ), 'a' );

				}  // phpcs:ignore

			} // phpcs:ignore

		jupiterx_close_markup_e( sprintf( 'jupiterx_social_share_inner[%1$s]', $markup_key ), 'div' );

	jupiterx_close_markup_e( sprintf( 'jupiterx_social_share[%1$s]', $markup_key ), 'div' );
}
