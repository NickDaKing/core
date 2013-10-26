<?php
    require_once 'lib/base.php';
    OC::checkMaintenanceMode();
    if (!isset($_GET['u'], $_GET['f'])) {
        header('HTTP/1.0 404 Not Found');
        not_found();
        exit;
    }

    $user = $_GET['u'];
    if (preg_match('/[^a-zA-Z0-9 _\.@\-]/', $user)) {
        header('HTTP/1.0 404 Not Found');
        not_found();
        exit;
    }
    $want = getcwd() . '/data/' . $user . '/files/Public';
    $have = realpath(getcwd() . '/data/' . $user . '/files/Public/'. $_GET['f']);
    if (strpos($have, $want, 0) === false) {
        header('HTTP/1.0 404 Not Found');
        not_found();
        exit;
    }

    if (file_exists($have) && ! is_dir($have)) {

        header('Content-Description: File Transfer');
        header('Content-Type: ' . mime_content_type($have));
        header('Content-Disposition: attachment; filename="' . basename($have) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Length:' . filesize($have));
        header("X-Sendfile: " . $have);
    } else {
        header('HTTP/1.0 404 Not Found');
        not_found();
        exit;
    }

function not_found() {
    $tmpl = new OC_Template( '', '404', 'guest' );
    $tmpl->printPage();
}
?>
