<?php
return array(
    // set your paypal credential
    'client_id' => 'Ae3MS5RiOF0vI_EKIGsnZC4lOKxkJycJFyrWreZ-exOg2nUdwO11GSJmZfc6zI22hamC6isMYHC_61if',
    'secret' => 'EJ3yIFo3qnDIUXxqb4QvTnYwJFnQQ4Gxqa5snVrbUxhxaRgkXuuOxWyXcrcBs9EmeYDo2kc4Wi3PSwDS',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);