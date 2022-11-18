<?php
# Ermöglicht es uns, zu regulären PHP-Funktionen auch WP-Funktionen zu verwenden, die für die Validierung entscheidend sind. Wie z. B., ob der Nutzer eingeloggt ist oder ob er eine bestimmte Role oder Cap hat.
require_once( '../../../wp-load.php' );

# Wenn wir keinen "file"-Parameter in der URL haben, beende das Skript.
$qv_file = $_GET[ 'file' ];
if( ! isset( $qv_file ) ) exit;

# Die eigentliche Validierung, ob eine gültige Anfrage vorliegt. Hier können beliebige WP-Funktionen wie "is_user_logged_in()", "current_user_can()" uvm. verwendet werden.
if( ! is_user_logged_in() ) {
	# Falls wir nicht eingeloggt sind, leite auf die Homepage weiter. Falls man den Nutzer zur Login-URL mit einem "redirect_to"-Parameter weiterleiten möchte, kann "auth_redirect()" (anstatt von "wp_safe_redirect()" oder "wp_redirect()") verwendet werden. Dies sorgt dafür, dass, nachdem man sich auf der weitergeleiteten Login-URL erfolgreich angemeldet, auf die gewünschte, am Anfang angeforderte Datei-URL, weitergeleitet wird.
	wp_safe_redirect( home_url() );
	exit;
}

# Definiere den Dateipfad.
$dir = wp_upload_dir();
$basedir = $dir[ 'basedir' ] . '/gesichert/';
$file = $basedir . $qv_file;

# Falls der Pfad oder die Datei aus irgendeinem Grund ungültig sind/nicht existieren, leite ebenfalls auf die Homepage weiter.
if( ! $basedir || ! is_file( $file ) ) {
	wp_safe_redirect( home_url() );
	exit;
}

# Überprüfe die Datei und bereite sie für den Browser vor.
$mime = wp_check_filetype( $file );

if( $mime[ 'type' ] == false ) {
	$mime[ 'type' ] = mime_content_type( $file );
}

header( 'Content-Type: ' . $mime[ 'type' ] );

# Sorge dafür, dass, wenn der Nutzer die Datei herunterlädt, der Dateiname dem im FTP-Client hochgeladenen immer identisch ist.
$filename = $qv_file;

if( str_contains( $qv_file, '/' ) ) {
	$filename = substr( $qv_file, strrpos( $qv_file, '/' ) + 1 );
}

# Kommentiere folgenden PHP-Header aus, falls du wünschst, dass die Datei direkt heruntergeladen werden soll.
# header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

# Lese letztendlich die Datei aus und beende das Skript.
readfile( $file );
exit;