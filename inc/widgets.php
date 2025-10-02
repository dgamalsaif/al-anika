<?php
/**
 * Custom Widgets for the Alam Al Anika Theme.
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register custom widgets.
 *
 * This function is hooked into the 'widgets_init' action.
 */
function alam_al_anika_register_custom_widgets() {
	// Example of how you would register a custom widget class:
	// register_widget( 'Alam_Al_Anika_Example_Widget' );
}
add_action( 'widgets_init', 'alam_al_anika_register_custom_widgets' );

/**
 * Example Custom Widget Class.
 *
 * You can uncomment and build upon this structure to create your own widgets.
 */
/*
class Alam_Al_Anika_Example_Widget extends WP_Widget {

	// Constructor: Sets up the widget name, etc.
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'alam_al_anika_example_widget',
			'description'                 => __( 'An example widget for the theme.', 'alam-al-anika' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'alam_al_anika_example_widget', __( 'Example Widget', 'alam-al-anika' ), $widget_ops );
	}

	// Outputs the content of the widget on the front-end.
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo esc_html__( 'Hello, World!', 'alam-al-anika' );
		echo $args['after_widget'];
	}

	// Outputs the options form on the admin side.
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'alam-al-anika' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'alam-al-anika' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	// Processes widget options to be saved.
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}
}
*/