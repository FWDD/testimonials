<?php
/**
 * Update messages for the custom post type
 *
 * @param $messages
 *
 * @return mixed
 */
function fwdd_testimonial_messages( $messages ) {
	global $post, $post_ID;
	$messages['testimonial'] = array(
		0  => '',
		1  => sprintf( __( 'Testimonial updated. <a href="%s">View testimonial</a>', 'fwdd-testimonials' ), esc_url( get_permalink( $post_ID ) ) ),
		2  => __( 'Custom field updated.', 'fwdd-testimonials' ),
		3  => __( 'Custom field deleted.', 'fwdd-testimonials' ),
		4  => __( 'Testimonial updated.', 'fwdd-testimonials' ),
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Testimonial restored to revision from %s', 'fwdd-testimonials' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => sprintf( __( 'Testimonial published. <a href="%s">View testimonial</a>', 'fwdd-testimonials' ), esc_url( get_permalink( $post_ID ) ) ),
		7  => __( 'Testimonial saved.', 'fwdd-testimonials' ),
		8  => sprintf( __( 'Testimonial submitted. <a target="_blank" href="%s">Preview testimonial</a>', 'fwdd-testimonials' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
		9  => sprintf( __( 'Testimonial scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview testimonial</a>', 'fwdd-testimonials' ), date_i18n( __( 'M j, Y @ G:i', 'fwdd-testimonials' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
		10 => sprintf( __( 'Testimonial draft updated. <a target="_blank" href="%s">Preview testimonial</a>', 'fwdd-testimonials' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'fwdd_testimonial_messages' );

/**
 * Adds the help tabs to the current page
 */
function fwdd_testimonial_help_tabs() {
	$screen = get_current_screen();

	if ( 'edit' === $screen->base && 'testimonial' === $screen->post_type ) {
		$tabs = array(
			array(
				'title'   => __( 'Overview', 'fwdd-testimonials' ),
				'id'      => 'RM_testimonials_overview',
				'content' => '<p>' . __( 'This screen provides access to client testimonials that can be used throughout the theme.', 'fwdd-testimonials' ) . '</p>'
			),
			array(
				'title'   => __( 'Using testimonials', 'fwdd-testimonials' ),
				'id'      => 'RM_testimonials_usage',
				'content' => '<p>' . __( 'To display the testimonials, use the testimonials widget. Click on <a href="widgets.php">Widgets</a> under Appearance.', 'fwdd-testimonials' ) . '</p>'
			)
		);

		foreach ( $tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}

		$screen->set_help_sidebar( '<a href="#">' . __('More info!', 'fwdd-testimonials') . '</a>' );
	}

	if ( 'post' === $screen->base && 'testimonial' === $screen->post_type ) {
		$tabs = array(
			array(
				'title'   => __( 'Title and Body', 'fwdd-testimonials' ),
				'id'      => 'RM_testimonials_overview',
				'content' => '<p>' . __( 'The title should be a short sentence, the main focus of the testimonial. The body can be larger and contain the entire text of the testimonial.', 'fwdd-testimonials' ) . '</p>'
			),
			array(
				'title'   => __('Testimonial Details', 'fwdd-testimonials'),
				'id'      => 'RM_testimonial_details',
				'content' => '<p>' . __( 'This box allows you to enter the name of the client, their job title, company name and website URL. If the client has a Gravatar account, you can display their Gravatar by entering their email address.', 'fwdd-testimonials' ) . '</p>'
			),
			array(
				'title'   => __('Featured Image', 'fwdd-testimonials' ),
				'id'      => 'RM_testimonial_image',
				'content' => '<p>' . __( 'The featured image will be shown next to the testimonial text as a small, thumbnail image. If an email address is entered for a client and they have a <a href="https://en.gravatar.com/">Gravatar</a> account, their Gravatar will be used instead.', 'fwdd-testimonials' ) . '</p>'
			)
		);

		foreach ( $tabs as $tab ) {
			$screen->add_help_tab( $tab );
		}

		$screen->set_help_sidebar( '<a href="#">' . __('More info!', 'fwdd-testimonials') . '</a>' );
	}
}

add_action( 'load-post-new.php', 'fwdd_testimonial_help_tabs' );
add_action( 'load-post.php', 'fwdd_testimonial_help_tabs' );
add_action( 'load-edit.php', 'fwdd_testimonial_help_tabs' );

/**
 * Add Meta boxes
 */
function fwdd_testimonial_meta_boxes() {
	remove_meta_box( 'wpseo_meta', 'testimonial', 'normal' );
	add_meta_box( 'testimonials-form', __( 'Testimonial Details', 'fwdd-testimonials' ), 'fwdd_testimonials_form', 'testimonial', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'fwdd_testimonial_meta_boxes' );

/**
 * Create the meta box form
 */
function fwdd_testimonials_form() {
	$post_id          = get_the_ID();
	$testimonial_data = get_post_meta( $post_id, '_testimonial', true );
	$client_name      = ( empty( $testimonial_data['client_name'] ) ) ? '' : $testimonial_data['client_name'];
	$client_title     = ( empty( $testimonial_data['client_title'] ) ) ? '' : $testimonial_data['client_title'];
	$client_email     = ( empty( $testimonial_data['client_email'] ) ) ? '' : $testimonial_data['client_email'];
	$source           = ( empty( $testimonial_data['source'] ) ) ? '' : $testimonial_data['source'];
	$link             = ( empty( $testimonial_data['link'] ) ) ? '' : $testimonial_data['link'];

	wp_nonce_field( 'testimonials', 'testimonials' );
	?>
	<p>
		<label><?php _e('Client\'s Name (optional)', 'fwdd-testimonials');?></label><br/>
		<input type="text" value="<?php echo $client_name; ?>" name="testimonial[client_name]" size="40"/>
	</p>
	<p>
		<label><?php _e('Client\'s Job title (optional)', 'fwdd-testimonials');?></label><br/>
		<input type="text" value="<?php echo $client_title; ?>" name="testimonial[client_title]" size="40"/>
	</p>
	<p>
		<label><?php _e('Client\'s email (optional- Used for Gravatar)', 'fwdd-testimonials');?></label><br/>
		<input type="text" value="<?php echo $client_email; ?>" name="testimonial[client_email]" size="40"/>
	</p>
	<p>
		<label><?php _e('Business/Site Name (optional)', 'fwdd-testimonials');?></label><br/>
		<input type="text" value="<?php echo $source; ?>" name="testimonial[source]" size="40"/>
	</p>
	<p>
		<label><?php _e('Website URL (optional)', 'fwdd-testimonials');?></label><br/>
		<input type="text" value="<?php echo $link; ?>" name="testimonial[link]" size="40"/>
	</p>
	<?php
}

/**
 * Add meta box data to save_post action
 * @param $post_id
 */
function fwdd_testimonials_save_post( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! empty( $_POST['testimonials'] ) && ! wp_verify_nonce( $_POST['testimonials'], 'testimonials' ) ) {
		return;
	}

	if ( ! empty( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( ! wp_is_post_revision( $post_id ) && 'testimonial' == get_post_type( $post_id ) ) {
		remove_action( 'save_post', 'fwdd_testimonials_save_post' );

		wp_update_post( array(
			'ID'         => $post_id
		) );

		add_action( 'save_post', 'fwdd_testimonials_save_post' );
	}

	if ( ! empty( $_POST['testimonial'] ) ) {
		$testimonial_data['client_name']  = ( empty( $_POST['testimonial']['client_name'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_name'] );
		$testimonial_data['client_title'] = ( empty( $_POST['testimonial']['client_title'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_title'] );
		$testimonial_data['client_email'] = ( empty( $_POST['testimonial']['client_email'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['client_email'] );
		$testimonial_data['source']       = ( empty( $_POST['testimonial']['source'] ) ) ? '' : sanitize_text_field( $_POST['testimonial']['source'] );
		$testimonial_data['link']         = ( empty( $_POST['testimonial']['link'] ) ) ? '' : esc_url( $_POST['testimonial']['link'] );

		update_post_meta( $post_id, '_testimonial', $testimonial_data );
	} else {
		delete_post_meta( $post_id, '_testimonial' );
	}
}

add_action( 'save_post', 'fwdd_testimonials_save_post' );


/**
 * Replaces default columns with our own.
 * @return array
 */
function testimonials_edit_columns() {
	$new_columns = array(
		'cb'                      => '<input type="checkbox" />',
		'title'                   => __( 'Title', 'fwdd-testimonials' ),
		'author'                  => __( 'Author', 'fwdd-testimonials' ),
		'testimonial-client-name' => __( 'Client\'s Name', 'fwdd-testimonials' ),
		'testimonial-source'      => __( 'Business/Site', 'fwdd-testimonials' ),
		'testimonial-link'        => __( 'Link', 'fwdd-testimonials' ),
		'date'                    => __('Date', 'fwdd-testimonials')
	);

	return $new_columns;
}

add_filter( 'manage_testimonial_posts_columns', 'testimonials_edit_columns', 10, 1 );


/**
 * Removes the WordPress SEO columns that we don't need for testimonials.
 * @param $columns
 *
 * @return mixed
 */
function testimonial_remove_columns( $columns ) {
	unset(
		$columns['wpseo-score'], $columns['wpseo-title'], $columns['wpseo-metadesc'], $columns['wpseo-focuskw']
	);

	return $columns;
}
// Hooks to filter: manage_edit-${post_type}_columns
add_filter( 'manage_edit-testimonial_columns', 'testimonial_remove_columns' );


/**
 * Outputs the text value for each column of the Custom Post Type
 * @param $column
 */
function testimonials_columns( $column ) {
	$testimonial_data = get_post_meta( get_the_id(), '_testimonial', true );
	switch ( $column ) {
		case 'testimonial-client-name':
			if ( ! empty( $testimonial_data['client_name'] ) ) {
				echo $testimonial_data['client_name'];
			}
			break;
		case 'testimonial-source':
			if ( ! empty( $testimonial_data['source'] ) ) {
				echo $testimonial_data['source'];
			}
			break;
		case 'testimonial-link':
			if ( ! empty( $testimonial_data['link'] ) ) {
				echo $testimonial_data['link'];
			}
			break;
	}
}

add_action( 'manage_posts_custom_column', 'testimonials_columns', 10, 2 );