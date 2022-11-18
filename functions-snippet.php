<?php

#region Protected File Download
function shortcodes_protected_upload_url() {
	$dir = wp_upload_dir();
	return $dir[ 'baseurl' ] . '/gesichert/';
}
add_shortcode( 'protected-upload-url', 'shortcodes_protected_upload_url' );
#endregion Protected File Download
