<?php
/**
 * Testimonials Widget
 */
class Testimonial_Widget extends WP_Widget {

	/**
	 * The text domain for i18n translations
	 * @var string
	 */
	public $text_domain;
	
	public function __construct() {
		$this->text_domain = 'rivalmind-testimonials';
		$widget_ops = array( 'classname' => 'testimonial_widget', 'description' => __('Display testimonial post type', 'rivalmind-testimonials') );
		parent::__construct( 'testimonial_widget', __('Testimonials', 'rivalmind-testimonials'), $widget_ops );
	}

	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$posts_per_page = (int) $instance['posts_per_page'];
		$orderby = strip_tags( $instance['orderby'] );
		$testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );

		echo $args['before_widget'];

		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		echo get_testimonials( $posts_per_page, $orderby, $testimonial_id );

		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
		$instance['orderby'] = strip_tags( $new_instance['orderby'] );
		$instance['testimonial_id'] = ( null == $new_instance['testimonial_id'] ) ? '' : strip_tags( $new_instance['testimonial_id'] );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'posts_per_page' => '1', 'orderby' => 'none', 'testimonial_id' => null ) );
		$title = strip_tags( $instance['title'] );
		$posts_per_page = (int) $instance['posts_per_page'];
		$orderby = strip_tags( $instance['orderby'] );
		$testimonial_id = ( null == $instance['testimonial_id'] ) ? '' : strip_tags( $instance['testimonial_id'] );
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', $this->text_domain);?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Number of Testimonials:', $this->text_domain);?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" type="text" value="<?php echo esc_attr( $posts_per_page ); ?>" />
		</p>

		<p><label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order By', $this->text_domain);?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
				<option value="none" <?php selected( $orderby, 'none' ); ?>><?php _e('None', $this->text_domain);?></option>
				<option value="ID" <?php selected( $orderby, 'ID' ); ?>><?php _e('ID', $this->text_domain);?></option>
				<option value="date" <?php selected( $orderby, 'date' ); ?>><?php _e('Date', $this->text_domain);?></option>
				<option value="modified" <?php selected( $orderby, 'modified' ); ?>><?php _e('Modified', $this->text_domain);?></option>
				<option value="rand" <?php selected( $orderby, 'rand' ); ?>><?php _e('Random', $this->text_domain);?></option>
			</select></p>

		<p><label for="<?php echo $this->get_field_id( 'testimonial_id' ); ?>"><?php _e('Testimonial IDs (optional)', $this->text_domain);?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'testimonial_id' ); ?>" name="<?php echo $this->get_field_name( 'testimonial_id' ); ?>" type="text" value="<?php echo $testimonial_id; ?>" />
		<i><?php _e('List of IDs to show only specific testimonials Example:1,3,10.', $this->text_domain);?></i>
		</p>
		<?php
	}
}

add_action( 'widgets_init', 'register_testimonials_widget' );
/**
 * Register widget
 *
 * This functions is attached to the 'widgets_init' action hook.
 */
function register_testimonials_widget() {
	register_widget( 'Testimonial_Widget' );
}