<?php
/**
 * fwdd Testimonials plugin functions file
 * Provides the functions needed globally for the plugin
 */

/**
 * Register the custom post type
 */
if ( ! function_exists( 'fwdd_create_testimonials_post_type' ) ) {
	function fwdd_create_testimonials_post_type() {
		$labels = array(
			'name'               => _x( 'Testimonials', 'Post Type General Name', 'fwdd-testimonials' ),
			'singular_name'      => _x( 'Testimonial', 'Post Type Singular Name', 'fwdd-testimonials' ),
			'menu_name'          => __( 'Testimonials', 'fwdd-testimonials' ),
			'name_admin_bar'     => __( 'Testimonials', 'fwdd-testimonials' ),
			'parent_item_colon'  => __( 'Parent Item:', 'fwdd-testimonials' ),
			'all_items'          => __( 'All Testimonials', 'fwdd-testimonials' ),
			'add_new_item'       => __( 'Add New Testimonial', 'fwdd-testimonials' ),
			'add_new'            => __( 'Add New', 'fwdd-testimonials' ),
			'new_item'           => __( 'New Testimonial', 'fwdd-testimonials' ),
			'edit_item'          => __( 'Edit Testimonial', 'fwdd-testimonials' ),
			'update_item'        => __( 'Update Testimonial', 'fwdd-testimonials' ),
			'view_item'          => __( 'View Testimonial', 'fwdd-testimonials' ),
			'search_items'       => __( 'Search Testimonials', 'fwdd-testimonials' ),
			'not_found'          => __( 'Not found', 'fwdd-testimonials' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'fwdd-testimonials' ),
		);
		$args   = array(
			'label'               => __( 'testimonial', 'fwdd-testimonials' ),
			'description'         => __( 'Client Testimonials', 'fwdd-testimonials' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-quote',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post'
		);
		register_post_type( 'testimonial', $args );
	}
}

/**
 * Get Testimonials
 *
 * @param  int $posts_per_page The number of testimonials you want to display
 * @param  string $orderby The order by setting  https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters
 * @param  array $testimonial_id The ID or IDs of the testimonial(s), comma separated
 *
 * @return  string  Formatted HTML
 */
if ( ! function_exists( 'get_testimonials' ) ) {
	function get_testimonials( $posts_per_page = - 1, $orderby = 'none', $testimonial_id = null ) {
		wp_enqueue_style('fwddt-styles');
		wp_enqueue_script('testimonials');
		$args = array(
			'posts_per_page' => (int) $posts_per_page,
			'post_type'      => 'testimonial',
			'orderby'        => $orderby,
			'no_found_rows'  => true,
			'post_status'    => 'publish',
		);
		if ( $testimonial_id ) {
			$args['post__in'] = array( $testimonial_id );
		}

		$query = new WP_Query( $args );
		$testimonials = '';
		if ( $query->have_posts() ) {
			//Start our testimonial slider
			$testimonials .= '<div class="fwdd-testimonials"><ul class="testimonials">';
			while ( $query->have_posts() ) : $query->the_post();
				$post_id          = get_the_ID();
				$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
				$client_name      = ( empty( $testimonial_data['client_name'] ) ) ? '' : esc_html($testimonial_data['client_name']);
				$client_title     = ( empty( $testimonial_data['client_title'] ) ) ? '' : '<br> ' . esc_html($testimonial_data['client_title']);
				$client_email     = ( empty( $testimonial_data['client_email'] ) ) ? '' : esc_html($testimonial_data['client_email']);
				$source           = ( empty( $testimonial_data['source'] ) ) ? '' : ' - ' . esc_html($testimonial_data['source']);
				$link             = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];
				$cite             = ( $link ) ? '<a href="' . esc_url( $link ) . '" target="_blank">' . $client_name . $client_title . $source . '</a>' : $client_name . $client_title . $source;
				$image ='';
				if ( ! empty( $client_email ) && get_avatar( $client_email ) ) {
					$image = get_avatar( $client_email );
				} elseif ( has_post_thumbnail() ) {
					$image = wp_get_attachment_image( get_post_thumbnail_id(), 'thumbnail' );
				}
				//TODO optionally show title
				//TODO use excerpt if there is one.
				$testimonials .= '<li>';
				$testimonials .= '<span class="testimonial-title">' . get_the_title() . '</span>';
				$testimonials .= '<span class="testimonial-content">';
				$testimonials .= '<span class="testimonial-text">' . get_the_content() . '<span></span></span>';
				$testimonials .= '<span class="testimonial-client-name">' . $image . '<cite>' . $cite . '</cite></span>';
				$testimonials .= '</span>';
				$testimonials .= '</li>';

			endwhile;
			wp_reset_postdata();
			$testimonials .= '</ul></div>';
		}

		return $testimonials;
	}
}


/**
 * Display Testimonials
 *
 * @param int $posts_per_page
 * @param string $orderby
 * @param null $testimonial_id
 */
if ( ! function_exists( 'the_testimonials' ) ) {
	function the_testimonials( $posts_per_page = - 1, $orderby = 'none', $testimonial_id = null ) {
		echo get_testimonials( $posts_per_page, $orderby, $testimonial_id );
	}
}

/**
 * Set initial options
 */
if ( ! function_exists( 'fwdd_init_options' ) ) {
	function fwdd_init_options() {
		update_option( 'fwdd_testimonials_version', FWDDT_VERSION );
		add_option( 'fwdd_testimonials_post_per_page', 10 );
	}
}

/**
 * Check the version number to see if an update is required
 */
if ( ! function_exists( 'fwdd_testimonials_update' ) ) {
	function fwdd_testimonials_update() {
		if ( get_option( 'fwdd_testimonials_version' ) >= FWDDT_VERSION ) {
			return;
		}
	}
}

if ( ! function_exists( 'load_testimonial_scripts' ) ) {
	function load_testimonial_scripts() {
		wp_register_style( 'fwddt-styles', plugins_url( 'css/testimonials.css', dirname( __FILE__ ) ) );
		wp_register_script( 'flexslider', plugins_url( 'js/jquery.flexslider-min.js', dirname( __FILE__ ) ), array( 'jquery' ), '2.5.0' );
		wp_register_script( 'testimonials', plugins_url( 'js/testimonials.js', dirname( __FILE__ ) ), array( 'flexslider' ), '1.0', true );
	}

	add_action( 'wp_enqueue_scripts', 'load_testimonial_scripts' );
}

/**
 * Add the At a Glance dashboard items
 */
add_action( 'dashboard_glance_items', 'prefix_add_dashboard_counts' );
function prefix_add_dashboard_counts() {
	$glancer = new Gamajo_Dashboard_Glancer;
	$glancer->add( 'testimonial' ); // show only published "testimonial" entries
}

function fix_glance_rmt_icon() { ?>
	<style>#dashboard_right_now .testimonial-count a:before { content: '\f122'; }</style>
	<?php
}
add_action( 'admin_head', 'fix_glance_rmt_icon' );

if ( ! function_exists( 'testimonials_shortcode' ) ):
	function testimonials_shortcode( $atts ) {
		$a = shortcode_atts( array(
			'posts_per_page' => -1,
			'orderby' => null,
			'testimonial_id'  => null,
		), $atts );

		return get_testimonials($a['posts_per_page'], $a['orderby'], $a['testimonial_id']);
	}
endif;

add_shortcode( 'testimonials', 'testimonials_shortcode' );