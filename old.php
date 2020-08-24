<?php

require_once 'core/vendor/autoload.php';
require_once 'config.php';


$client = new Google_Client();
$client->setClientId( CLIENT_ID );
$client->setClientSecret( CLIENT_SECRET );
$client->setRedirectUri( REDIRECT_URI );
$client->addScope( SCOPES );
$client->setAccessType( 'offline' );
$client->setApprovalPrompt('force');

/** Проверяем существование и создаем токена */
if ( isset( $_GET['code'] ) && ! isset( $_SESSION['token'] ) ) {
	$_SESSION['token'] = $client->fetchAccessTokenWithAuthCode( $_GET['code'] );
	header( 'location:/' );
}

if ( isset( $_SESSION['token'] ) ) {

	$client->setAccessToken( $_SESSION['token'] );

	var_dump($_SESSION['token']);

	if ( $client->isAccessTokenExpired() ) {

		$refreshTokenSaved = $client->getRefreshToken();

		$client->fetchAccessTokenWithRefreshToken( $client->getRefreshToken() );

		$accessTokenUpdated = $client->getAccessToken();

		$accessTokenUpdated['refresh_token'] = $refreshTokenSaved;

		$_SESSION['token'] = json_encode($accessTokenUpdated);

		$client->setAccessToken($accessTokenUpdated);

	}

	$google_service = new Google_Service_Oauth2( $client );
	$data           = $google_service->userinfo->get();
	var_dump( $data );

} else {
	echo '<a href="' . $client->createAuthUrl() . '">Login with Google</a>';
}