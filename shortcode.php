<?php

use Jenssegers\Date\Date;

/**
 * The main function the creates the feed from a shortcode.
 * Can be safely added directly to templates using
 * 'echo do_shortcode( "[asteriski-next-events]" );'
 */
/** @link display_asteriski_next_events() */
add_shortcode('asteriski-next-events', 'display_asteriski_next_events');

/**
 * @noinspection PhpUnusedParameterInspection
 * @throws \Google\Exception
 */
function display_asteriski_next_events( $atts = array(), $preview_settings = false ) {
    /**
     * Returns an authorized API client.
     */
    $client = new Google_Client();
    //$this->client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setScopes(
        'https://www.googleapis.com/auth/calendar.events.readonly'
    );
    $client->setAuthConfig(__DIR__ . '/credentials/credentials.json');
    // Get the API client and construct the service object.
    $service = new Google_Service_Calendar($client);
    
    $calendarId = 'asteriskiry@gmail.com';
    if (empty($atts['how_many'])) {
        $how_many = 7;
    } else {
        $how_many = $atts['how_many'];
    }
    $optParams = array(
        'maxResults' => $how_many,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();
    
    Date::setLocale(get_locale());
    ob_start(); ?>
    <div class='calendar-widget'>
    <?php
if (empty($events)) {
    _e('Upcoming events not found.', 'asteriski-next-events');
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
    $day = ucfirst($date->format('l d.m.'));
    $oldDay = ucfirst($oldDate->format('l d.m.'));
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
        $startTime = $date->format('H:i');
        $eTime = new Date($event->end->dateTime);
        $endTime = $eTime->format('H:i'); ?>
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
    $html = ob_get_contents();
    ob_get_clean();
    return $html;
}