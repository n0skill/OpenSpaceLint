<?php

class Email
{
    public static function send($subject, $receiver = "", $message)
    {
        global $logger;
        
        $emails = json_decode(CACHE_REPORT_BCC, true);
    
        if($receiver != "")
            $emails[] = $receiver;
        
        foreach($emails as $index => $email)
        {
            // The last argument '-femail@example.com' is used to set the Return-Path header.
            // Setting it as a regular header fails, it's removed from the mail server.
            mail($email, $subject, $message,
                "FROM: " . CACHE_REPORT_FROM . "\r\n".
                "Reply-To: " . CACHE_REPORT_FROM . "\r\n".
                "Message-ID: <" . time() . "." . CACHE_REPORT_FROM . ">\r\n".
                "X-Mailer: OpenSpaceLint\r\n",
                "-f". CACHE_REPORT_FROM
            );
            
            $logger->logNotice("Report mail sent to $email");
        }
    }
    
    /**
     * @param string $encoded_string An email which can be base64 encoded
     */
    public static function get($encoded_string)
    {
        if(base64_decode($encoded_string, true) !== false)
            return base64_decode($encoded_string);
        
        // here we assume that the string is not encoded
        return $encoded_string;
    }
}