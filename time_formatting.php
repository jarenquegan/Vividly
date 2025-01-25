<?php
function format_date_time($date_string, $format = 'F j, Y \a\t g:i A')
{
    // Handle null values
    $date_string = $date_string ?? '';

    // Create DateTime object
    $datetime = new DateTime($date_string);

    // Format and return the date and time
    return $datetime->format($format);
}

function format_time_difference($date_string)
{
    // Handle null values
    $date_string = $date_string ?? '';

    // Create DateTime objects
    $createdDateTime = new DateTime($date_string);
    $currentDateTime = new DateTime('now', $createdDateTime->getTimezone());

    // Calculate the difference between the two dates
    $interval = $currentDateTime->diff($createdDateTime);

    // Format the output based on the difference
    if ($interval->y > 0) {
        return ($interval->y == 1) ? '1 year ago' : $interval->format('%y years ago');
    } elseif ($interval->m > 0) {
        return ($interval->m == 1) ? '1 month ago' : $interval->format('%m months ago');
    } elseif ($interval->d >= 7) {
        $weeks = floor($interval->d / 7);
        return ($weeks == 1) ? '1 week ago' : $weeks . ' weeks ago';
    } elseif ($interval->d > 0) {
        return ($interval->d == 1) ? '1 day ago' : $interval->format('%d days ago');
    } elseif ($interval->h > 0) {
        return ($interval->h == 1) ? '1 hour ago' : $interval->format('%h hours ago');
    } elseif ($interval->i > 0) {
        return ($interval->i == 1) ? '1 minute ago' : $interval->format('%i minutes ago');
    } else {
        return 'Less than a minute ago';
    }
}

?>