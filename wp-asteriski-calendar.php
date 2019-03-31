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

require_once 'vendor/autoload.php';

if (! defined('ABSPATH')) {
    exit;
}

use Jenssegers\Date\Date;

/**
 * Adds Next_Events widget.
 */

class Next_Events extends WP_Widget
{
    private $client;

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {

        /**
         * Returns an authorized API client.
         */
        $this->client = new Google_Client();
        //$this->client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $this->client->setScopes(
            "https://www.googleapis.com/auth/calendar.events.readonly"
        );
        $this->client->setAuthConfig(__DIR__ . '/credentials/credentials.json');
        parent::__construct(
            'next_events',
            'Tulevat tapahtumat',
            array( 'description' => 'Widgetti joka näyttää tulevat tapahtumat Google kalenterista.' )
        );
    }

    /**
     * Backend widget form.
     */
    public function form($instance)
    {
        $title = ! empty($instance['title']) ? $instance['title'] : 'Tulevat tapahtumat';
        $howmany = ! empty($instance['howmany']) ? $instance['howmany'] : ''; ?>
<p> Otsikko:
    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php 'Otsikko: '; ?></label> 
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
</p>
<p>Monta tapahtumaa näytetään:
    <label for="<?php echo esc_attr($this->get_field_id('howmany')); ?>"><?php 'Tapahtumien määrä: '; ?></label> 
    <input class="widefat" id="<?php echo esc_attr($this->get_field_id('howmany')); ?>" name="<?php echo esc_attr($this->get_field_name('howmany')); ?>" type="text" value="<?php echo esc_attr($howmany); ?>">
</p>
<?php
    }

    /**
     * Frontend display of widget.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (! empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Get the API client and construct the service object.
        $service = new Google_Service_Calendar($this->client);

        $calendarId = 'asteriskiry@gmail.com';
        if (empty($instance['howmany'])) {
            $howmany = 7;
        } else {
            $howmany = $instance['howmany'];
        }
        $optParams = array(
            'maxResults' => $howmany,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        Date::setLocale('fi');

        // Widget content ?>
        <div class="calendar-widget">
        <?php
        if (empty($events)) {
            print "Tulevia tapahtumia ei löytynyt.\n";
        } else {
            $oldDate = new Date('2000-01-01');
            foreach ($events as $event) {
                $hasTime = true;
                $dateTemp = $event->start->dateTime;
                $date = new Date($dateTemp);
                if (empty($dateTemp)) {
                    $date = new Date($event->start->date);
                    $hasTime = false;
                }
                $day = ucfirst($date->format('l d.m.Y'));
                $oldDay = ucfirst($oldDate->format('l d.m.Y'));
                if ($oldDay != $day) {
                    ?>
                    <div class="event-day">
                        <h4><?php echo $day ?></h4>
                    <?php
                } ?>
                    <div class="calendar-event">
                        <p class="event-name"><i class="fas fa-calendar-alt"></i> <?php echo $event->getSummary() ?></p>
                <?php
                if ($hasTime) {
                    $startTime = $date->format('H:s');
                    $eTime = new Date($event->end->dateTime);
                    $endTime = $eTime->format('H:s'); ?>
                        <p class="event-time"><i class="fas fa-clock"></i> <?php echo $startTime . ' - ' . $endTime . '</p>';
                } ?>
                    </div>
                <?php
                if ($oldDay != $day) {
                    echo '</div>';
                }
                $oldDate = $date;
            }
        } ?>
</div>
<?php

        echo $args['after_widget'];
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (! empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['howmany'] = (! empty($new_instance['howmany'])) ? sanitize_text_field($new_instance['howmany']) : '';

        return $instance;
    }
}

// register Next_Events widget
function register_next_events()
{
    register_widget('Next_Events');
}

add_action('widgets_init', 'register_next_events');

/**
 * Load CSS
 */
function calendar_enqueue_css()
{

    /* Register */
    wp_register_style('main-css', plugins_url('css/main.css', __FILE__));

    /* Enqueue */
    wp_enqueue_style('main-css');
}

add_action('wp_enqueue_scripts', 'calendar_enqueue_css');
