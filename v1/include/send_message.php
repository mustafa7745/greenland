<?php
require __DIR__ . '/vendor/autoload.php';
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendFCM
{
    function sendMessageToDevice(): bool
    {
        $json = json_encode(
            array(
                "type" => "service_account",
                "project_id" => "greenland-rest",
                "private_key_id" => "   ",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDEPK47RnRrLH+m\nUVHVtlfG2pX1o9ecd1Iiab7q3mzPKfjRU9zLZ1Q01ZF30E/FVtI8pWpni5nCiDyh\nprkDSUmzBzIgOwL9I1nDIFB6tJMvT3z8FId68bFczGanSOQorCZsJmvMLhT2GEu8\n8Cx+I9nSFGJf/IS2reKAL6Z9IZQW1NyFyFhs1uFJ36y5AoW2CdIxQkCK06zfbqXg\nqniVwPDxYnEmZ0HU8sPxeIcsmHSU91y5rFNyBTWNe7uyBULh4As16cVUD6Z2JxbH\nEcBj2x+6HT7Ml9iEntDTXVaAUi5W8OuxWv0QcDoYKxPbL6hSUswh2SGmMgA2WKdz\np39k0zaPAgMBAAECggEAAyPYGIliEizzcJ0wgAjw5/UAx/NyicW8V2H1yhr7sWeX\nMaeeitVWcESn6pDiFeLzw5X/WTTPUGDkXM8m0A9Q0eqK5Mp3m8arCFzwrLtNokjV\nU9CdnQatCIfExLx9L3bBuU1MFZz50jjv9/qn25owlr9OFlcbZd5BF5Krnqj2OP9G\nZH2b440OpzuflFI02uuY81no1P5DvuhrM+VpiRU/fxldVrIYxJSWnru9aAk1mFFa\nclGacAjAEJCWKYT/K0252reWXS7d9j3oFbItfFk5kVPatulKkzh994dC6FnPipsV\nC0jdyLjEYrdbBzKCMyegwgZODsY8ovnE5y14Dp/8kQKBgQD+/lOwWrt/v75D6CP9\ny/jqneCU1GA0qdVfK0AmaFQH7ONV5iS2CSLsyOMQB5ImgYX7KiT9lh7lBeYQVNXz\nBtoS0bVLuI1hVk8FiA9Lpq798//6MqR9rrW/UkUa0T2A9+QC7+2QYCIQaWbjt+RL\n3Tv7bfFrF7IqoJdHN/z8Xn0JTQKBgQDFAvrUYUvUXh8YbskWNH5NOSw+xxvk9pNj\n7RZR5M/hm/dE5jx+H3U5InlwXS8+kVb+Cjor9XyBW6Wak0JZDwvfOGZWRCOY/lmy\nTJ3AxpnX6jjssbXMog8vxSX1zDMjGRR7I0KGLXxGc8CrhwIcF41Q3nKtquh2FRYy\nRS1iSjDxSwKBgQCeTmjIkNvdCL5nzEQj+bEUf2WBIISFzXZxehl+fsDEltXga1wZ\nQ3zSQjltpzWeAEWc0+JZKQ0PJGVbeD/HfFaA3n7OfsoPRxUSGxFb5yS29vfRbgVf\nHcsp9zp91q93VO7Sv//d5UDgrX86Gt16F+R4SR5bXT+4ZTDl/yVpzIOV9QKBgCBa\nDuqVV6h2FFEk0CyvThZMTzG37KK6wxVjt4iXNHPt8rsDu+dSLyPEv4BuLPXvVAO/\nljHlzB5J+HXbvMd3KxHq5xM/eUEEc6JN5pHjixvjwJlNzXbfHfNQQp2MfNFEqxJV\nhfUWvxOqqncAYp5OV8xx2w8dw7KwX8a9iWhcmpDlAoGBAOyBAhENaIzhsCcADBXm\nNu+4xmX+fzlNEc516BKRFOU0frWLIT/EClVcoKPWGr97ns6foa70P1AEQ/VJWOTJ\nlcqy8ZzFwZa1nndQ2C8bb8djJloS4/l1j7jX5C/IlOKCMZGdutxhEDQZ8g4RC+b9\nzPcX/OdoTFy7XSwsuiZY6bbE\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-o1sxa@greenland-rest.iam.gserviceaccount.com",
                "client_id" => "102117280433581972412",
                "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
                "token_uri" => "https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-o1sxa%40greenland-rest.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            )
        );
        $factory = (new Factory)->withServiceAccount($json);
        $messaging = $factory->createMessaging();

        $deviceToken = 'dqHids3eTMKdytKY5CqADO:APA91bGyjOPccIViQkz1eX3_zPjuWDAI3gw7CuOaH9QoLz5g_wmjSV7MXr83itSmvyt82gpxIOEIquTgra_4sPs0-i5sW871TURoD8PvNW_7og7TORjGjuunFx0U4qEx6ak2kCI3RsWX';

        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification(Notification::create('تم اضافة فئة جديدة', 'جديد على الاطلاق'));

        try {
            $messaging->send($message);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function sendMessageToTobic($json, $topic, $title, $body): bool
    {

        try {
            $factory = (new Factory)->withServiceAccount($json);
        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(Notification::create($title, $body));
        $messaging->send($message);
            return true;
        } catch (\Throwable $th) {
            // print_r($th->getMessage());
            return false;
        }
    }
    
    function sendMessageToOne($json, $token, $title, $body): bool
    {

        try {
            $factory = (new Factory)->withServiceAccount($json);
        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body));
        $messaging->send($message);
            return true;
        } catch (\Throwable $th) {
            // print_r($th->getMessage());
            return false;
        }
    }


}

// {
//     "type": "service_account",
//     "auth_uri": "https://accounts.google.com/o/oauth2/auth",
//     "client_id": "102117280433581972412",
//     "token_uri": "https://oauth2.googleapis.com/token",
//     "project_id": "greenland-rest",
//     "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDEPK47RnRrLH+m\nUVHVtlfG2pX1o9ecd1Iiab7q3mzPKfjRU9zLZ1Q01ZF30E/FVtI8pWpni5nCiDyh\nprkDSUmzBzIgOwL9I1nDIFB6tJMvT3z8FId68bFczGanSOQorCZsJmvMLhT2GEu8\n8Cx+I9nSFGJf/IS2reKAL6Z9IZQW1NyFyFhs1uFJ36y5AoW2CdIxQkCK06zfbqXg\nqniVwPDxYnEmZ0HU8sPxeIcsmHSU91y5rFNyBTWNe7uyBULh4As16cVUD6Z2JxbH\nEcBj2x+6HT7Ml9iEntDTXVaAUi5W8OuxWv0QcDoYKxPbL6hSUswh2SGmMgA2WKdz\np39k0zaPAgMBAAECggEAAyPYGIliEizzcJ0wgAjw5/UAx/NyicW8V2H1yhr7sWeX\nMaeeitVWcESn6pDiFeLzw5X/WTTPUGDkXM8m0A9Q0eqK5Mp3m8arCFzwrLtNokjV\nU9CdnQatCIfExLx9L3bBuU1MFZz50jjv9/qn25owlr9OFlcbZd5BF5Krnqj2OP9G\nZH2b440OpzuflFI02uuY81no1P5DvuhrM+VpiRU/fxldVrIYxJSWnru9aAk1mFFa\nclGacAjAEJCWKYT/K0252reWXS7d9j3oFbItfFk5kVPatulKkzh994dC6FnPipsV\nC0jdyLjEYrdbBzKCMyegwgZODsY8ovnE5y14Dp/8kQKBgQD+/lOwWrt/v75D6CP9\ny/jqneCU1GA0qdVfK0AmaFQH7ONV5iS2CSLsyOMQB5ImgYX7KiT9lh7lBeYQVNXz\nBtoS0bVLuI1hVk8FiA9Lpq798//6MqR9rrW/UkUa0T2A9+QC7+2QYCIQaWbjt+RL\n3Tv7bfFrF7IqoJdHN/z8Xn0JTQKBgQDFAvrUYUvUXh8YbskWNH5NOSw+xxvk9pNj\n7RZR5M/hm/dE5jx+H3U5InlwXS8+kVb+Cjor9XyBW6Wak0JZDwvfOGZWRCOY/lmy\nTJ3AxpnX6jjssbXMog8vxSX1zDMjGRR7I0KGLXxGc8CrhwIcF41Q3nKtquh2FRYy\nRS1iSjDxSwKBgQCeTmjIkNvdCL5nzEQj+bEUf2WBIISFzXZxehl+fsDEltXga1wZ\nQ3zSQjltpzWeAEWc0+JZKQ0PJGVbeD/HfFaA3n7OfsoPRxUSGxFb5yS29vfRbgVf\nHcsp9zp91q93VO7Sv//d5UDgrX86Gt16F+R4SR5bXT+4ZTDl/yVpzIOV9QKBgCBa\nDuqVV6h2FFEk0CyvThZMTzG37KK6wxVjt4iXNHPt8rsDu+dSLyPEv4BuLPXvVAO/\nljHlzB5J+HXbvMd3KxHq5xM/eUEEc6JN5pHjixvjwJlNzXbfHfNQQp2MfNFEqxJV\nhfUWvxOqqncAYp5OV8xx2w8dw7KwX8a9iWhcmpDlAoGBAOyBAhENaIzhsCcADBXm\nNu+4xmX+fzlNEc516BKRFOU0frWLIT/EClVcoKPWGr97ns6foa70P1AEQ/VJWOTJ\nlcqy8ZzFwZa1nndQ2C8bb8djJloS4/l1j7jX5C/IlOKCMZGdutxhEDQZ8g4RC+b9\nzPcX/OdoTFy7XSwsuiZY6bbE\n-----END PRIVATE KEY-----\n",
//     "client_email": "firebase-adminsdk-o1sxa@greenland-rest.iam.gserviceaccount.com",
//     "private_key_id": "   ",
//     "universe_domain": "googleapis.com",
//     "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-o1sxa%40greenland-rest.iam.gserviceaccount.com",
//     "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs"
// }
