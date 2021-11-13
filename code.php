<?php
    require_once 'vendor/autoload.php';
    require_once 'config.php';

    use GuzzleHttp\Client;
    use GuzzleHttp\Psr7;
    use GuzzleHttp\Exception\ClientException;

    function searchUserQuery () {

        $accessToken = NULL;

        if( !empty($_SESSION['user']) ) {
            $_SESSION['user']['access_token'];
            $_SESSION['user']['token_type'];

            $accessToken = $_SESSION['user']['token_type']. ' ' . $_SESSION['user']['access_token'];
        }

        try {
            // code...
            // $accessToken = 'Bearer '.'BQCCaFoziZNKrFns1mcSfOC9_WWlXj8V9_D7y2_Qf6GejIsdHEuDc5GGtPgyefXrRIeBwZntbzTT1vCGqeccQBybrmlqDIq2RC15DShq5JnNUTO1SdVOqI7wcEvQbDDz6TsrTln_zQ0Mrf28Gbe09LsvdUBrJwgIqw';
    
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => 'https://api.spotify.com',
            ]);
    
            $query  = !empty($_GET['artist']) ? $_GET['artist'] .' '. $_GET['title'] : $_GET['title'];
            $type   = !empty($_GET['artist']) ? "artist,track" : "track";
            
            $response = $client->request('GET', '/v1/search', [
                'headers' => [
                    'Authorization' => $accessToken,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'page'      => '1',
                    'q'         => $_GET['artist'] .' '. $_GET['title'],
                    'type'      => "artist,track",
                    'offset'    => $_GET['offset'],
                    'limit'     => 10
                ]
            ]);

            //get status code using $response->getStatusCode(); 
            
            $body = $response->getBody();
            
            return json_decode($body, true);
    
        } catch (ClientException $e) {
            if( $e->getCode() === 401) getAccessAndRefreshToken($credentials);
        }
    }

    function getAccessAndRefreshToken ($credentialsArr) {
        try {
            $client = new Client();

            $tokenUrl = "https://accounts.spotify.com/api/token";

            $headers = [
                "Authorization" => "Basic ". $credentialsArr['encoded_base']
            ];

            $options = [
                'form_params' => [
                    "grant_type"    => "client_credentials",
                    // "code"          => $_GET['code'],
                    // "redirect_uri"  => $credentialsArr['redirect_uri']
                ],
                "headers" => $headers
            ];
            
            $response = $client->post($tokenUrl, $options);
        
            if($response->getStatusCode() == 200) {
                $responseContents   = $response->getBody();
                $tokenResponse      = json_decode($responseContents, true);
                $_SESSION['user']   = $tokenResponse;

                header("Refresh:0");
            } 

        } catch (ClientException $e) {
            if( $e->getCode() === 400) {
                unset($_SESSION['user']);
                header("Refresh:0");
            }
            echo Psr7\str($e->getResponse());
            exit;
        }
    }

    // if( !empty($_POST) ){
    //     echo "<pre>";
    //     echo "i am here";
    //     print_r($_POST);
    //     exit;


    //     try {
    //         $client = new Client();

    //         // const scriptURL = 'https://script.google.com/macros/s/AKfycbyEsuqnHRAoHWxL4QesdPMiyIYdEcKPpwaDXmpVFkyye2cUTfZ8OTGqPxoXn5c7yjZJ/exec'

    //         $scriptUrl = "https://script.google.com/macros/s/AKfycbyEsuqnHRAoHWxL4QesdPMiyIYdEcKPpwaDXmpVFkyye2cUTfZ8OTGqPxoXn5c7yjZJ/exec";

    //         $options = [
    //             'form_params' => $_POST
    //         ];           
            
    //         $response = $client->post($scriptUrl, $options);
        
    //         if($response->getStatusCode() == 200) {
              
    //         } 

    //     } catch (ClientException $e) {
    //         if( $e->getCode() === 400) {
                
    //         }
    //         echo Psr7\str($e->getResponse());
    //         exit;
    //     }
    // }
?>