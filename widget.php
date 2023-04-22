<?php

/**
 * Adds Next_Events widget.
 */
class Next_Events extends WP_Widget
{
	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct()
	{
		parent::__construct(
			'asteriski_next_events',
			__('Asteriski\'s upcoming events', 'asteriski-next-events'),
			array('description' => __('Widget which shows the upcoming events from Google Calendar.', 'asteriski-next-events'))
		);
	}

    /**
     * Backend widget form.
     */
    public function form($instance)
    {
		$title = $instance['title'] ?? __('Upcoming events', 'asteriski-next-events');
		$how_many = ! $instance['how_many'] ?? ''; ?>
		
<p>
	<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
	<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
    <label for="<?php echo esc_attr($this->get_field_id('how_many')); ?>"><?php esc_html_e( 'Amount:' ); ?></label>
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('how_many')); ?>" name="<?php echo esc_attr($this->get_field_name('how_many')); ?>" type="number" value="<?php echo esc_attr($how_many); ?>">
</p>
<?php
    }

    /**
     * Frontend display of widget.
     */
    public function widget($args, $instance)
    {
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$content = isset( $instance['content'] ) ? strip_tags( $instance['content'] ) : '[asteriski-next-events]';
	
		echo $args['before_widget'];
	
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
		}
		echo do_shortcode( $content );
	
		echo $args['after_widget'];
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update($new_instance, $old_instance):array
    {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['how_many'] = (! empty($new_instance['how_many'])) ? strip_tags($new_instance['how_many']) : '';

        return $instance;
    }
}

/**
 * Add Next_Events widget
 */
add_action('widgets_init', 'register_asteriski_next_events');
function register_asteriski_next_events(): void
{
	register_widget('Next_Events');
}
add_action('widgets_init', 'register_asteriski_next_events');

// allow shortcode in widgets
add_filter( 'widget_text', 'do_shortcode' );