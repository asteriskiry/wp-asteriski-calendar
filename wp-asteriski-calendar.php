<?php

/**
 * Plugin Name: WP Asteriski Calendar
 * Description: Google kalenterista "Tulevat tapahtumat"
 * Plugin URI: https://asteriski.fi
 * Author: Maks Turtiainen, Asteriski ry
 * Version: 1.0
 * Author URI: https://github.com/asteriskiry
 * License: MIT
 **/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds Next_Events widget.
 */

class Next_Events extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'next_events',
            'Tulevat tapahtumat',
            array( 'description' => 'Widgetti joka näyttää tulevat tapahtumat Google kalenterista.' )
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        if ( ! empty( $instance['apikey'] ) ) {
            echo $args['before_apikey'] . apply_filters( 'widget_apikey', $instance['apikey'] ) . $args['after_apikey'];
        }
        /* Widget content */
        ?>
        <div class="calendar-widget">
            <?php for ($i = 0; $i < 5; $i++) { ?>
            <div class="calendar-event">
                <h4>Maanantai 13.08.</h4>
                <h6>Esimerkkitapahtuma</h6>
                <p><i class="fas fa-clock"></i> 12:00 - 14:00</p>
            </div>
            <?php } ?>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Tulevat tapahtumat';
        $apikey = ! empty( $instance['apikey'] ) ? $instance['apikey'] : '';
?>
<p> Otsikko:
    <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php 'Otsikko: '; ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
</p>
<p>Google API key:
    <label for="<?php echo esc_attr( $this->get_field_id( 'apikey' ) ); ?>"><?php 'Google API key: '; ?></label> 
    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'apikey' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'apikey' ) ); ?>" type="text" value="<?php echo esc_attr( $apikey ); ?>">
</p>
<?php 
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['apikey'] = ( ! empty( $new_instance['apikey'] ) ) ? sanitize_text_field( $new_instance['apikey'] ) : '';

        return $instance;
    }

}

// register Next_Events widget
function register_next_events() {
    register_widget( 'Next_Events' );
}

add_action( 'widgets_init', 'register_next_events' );
