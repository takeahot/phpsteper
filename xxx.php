
if ( !is_multisite() ) {
	wp_redirect( site_url( '/wp-login.php?action=register' ) );
	die();
}