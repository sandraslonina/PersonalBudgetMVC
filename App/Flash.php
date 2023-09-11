<?php

namespace App;

/**
 * 
 * Flash notificationn messages: message for one-time display using the session 
 * for storage between requests.
 * 
 * PHP version 7.0
 * 
 */
class Flash
{

    const SUCCESS = 'success';

    const INFO = 'info';

    const WARNING = 'warning';

    public static function addMessage($message, $type = 'success')
    {
        // Create array in the session if it doesn't already exist
        if (!isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = [];
        }

        // Append the message to the array
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $type
        ];
    }

    /**
     * Get all the messages
     *
     * @return mixed  An array with all the messages or null if none set
     */
    public static function getMessages()
    {
        if (isset($_SESSION['flash_notifications'])) {
            //return $_SESSION['flash_notifications'];
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
        }
    }
}