<?php

namespace ahvla\SendGridService;

use Config;
use Log;

class SendGrid {

    public function Send($params) {

        $to = '';
        if (isset($params['to'])&&(!empty($params['to']))) {
            $to = $params['to'];
        }
        else {
            throw new Exception('Sendgrid no to.');
        }

        $subject = '';
        if (isset($params['subject'])&&(!empty($params['subject']))) {
            $subject = $params['subject'];
        }
        else {
            throw new Exception('Sendgrid no subject.');
        }

        $message = '';
        if (isset($params['message'])&&(!empty($params['message']))) {
            $message = $params['message'];
        }
        else {
            throw new Exception('Sendgrid no subject.');
        }

        $from = Config::get('ahvla.emailreply');
        if (isset($params['from'])&&(!empty($params['from']))) {
            $from = $params['from'];
        }

        $api_user = Config::get('ahvla.sendgrid_api_user');
        $api_key = Config::get('ahvla.sendgrid_api_key');

        $fields = array(    'to' => $to,
                            'subject' => $subject,
                            'html' => $message,
                            'from' => $from,
                            // 'x-smtpapi' => '{"filters":{"templates":{"settings":{"enable":1,"template_id":"6770c11f-97d5-4be9-8811-c86525799ec9"}}}}'
                            );

        $fields_string = http_build_query($fields);

        // create curl resource
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Authorization: Bearer '.Config::get('ahvla.sendgrid_api_key') ) );

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://api.sendgrid.com/api/mail.send.json");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields);

        // $output contains the output string
        try {
            $output = curl_exec($ch);

            Log::info('SendGrid', array('mail-params' => $fields, 'output' => $output));

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        // close curl resource to free up system resources
        curl_close($ch);

        return true;
    }

}
