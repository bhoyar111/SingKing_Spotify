<?php
    $clientId       = "d50a94b495ec42ad9de2b6a5d890aa23";
    $clientSecret   = "16c2ded7479d4a22bff4c0ab5b4d23e1";

    $base64Enoded = base64_encode($clientId.':'.$clientSecret);

    $authCodeUrl = "https://accounts.spotify.com/authorize?";

    $scope = "user-read-private%20user-read-email"; //"ugc-image-upload";

    $response_type = "token"; //"code";
    
    $websiteUrl = "http://localhost/project/singking/";

    $redirectURI = $websiteUrl."code.php";

    $redirectEncodedURI = urlencode($redirectURI);
    
    $credentials = [
        "client_id"         => $clientId,
        "client_secret"     => $clientSecret,
        "encoded_base"      => $base64Enoded,
        "redirect_uri"      => $redirectURI, 
        "website_url"       => $websiteUrl,
        "auth_code_link"    => $authCodeUrl."client_id=".$clientId."&scopes=".$scope."&response_type=".$response_type."&redirect_uri=".$redirectEncodedURI."&state=123"
    ];
?>