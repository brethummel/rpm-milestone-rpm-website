<?php

$version = '1.0.0';

// ======================================= //
//          CALL UPDATE CSS HOOK           //
// ======================================= // 

function spr_update_css_init() {
    $themeurl = get_template_directory_uri();
    $themedir = get_template_directory();
    spr_update_css($themeurl, $themedir, $GLOBALS['stageuser'], $GLOBALS['stagepass']);
}
add_action( 'wp_enqueue_scripts', 'spr_update_css_init' );


// ======================================= //
//    COMPILE STYLE.CSS W/ SCSS UPDATES    //
// ======================================= // 

function spr_check_for_css_updates($themedir) {
    $style_date = date(filemtime($themedir . '/style.css')); // get modified date of style.css
    $scss_files = spr_get_file_list($themedir, '/\.scss$/i');
    $updated = false;
    foreach($scss_files as $filename) {
        if (filemtime($filename) > $style_date
            && !strpos($filename, '_custom.scss')
            && !strpos($filename, '_blocks_admin.scss')
            && !strpos($filename, '_blocks.scss')
        ){
            // echo('<pre>'); print_r('found an updated file!'); echo('</pre>');
            $updated = true;
            break;
        }
    }
    return $updated;
}

function spr_update_css($themeurl, $themedir, $stageuser, $stagepass, $force=false) {
    if (spr_check_for_css_updates($themedir) || filesize($themedir . '/style.css') < 500 || filesize($themedir . '/admin.css') < 500 || $force == true) {
        // echo('<pre>'); print_r('okay about to update css files!'); echo('</pre>');
        $style_css = file_get_contents($themedir . '/style.css');
        $admin_css = file_get_contents($themedir . '/admin.css');
        if (strpos($style_css, '/* compiled by scssphp')) {
            $style_css = substr($style_css, 0, strpos($style_css, '/* compiled by scssphp'));
        }
        if (strpos($admin_css, '/* compiled by scssphp')) {
            $admin_css = substr($admin_css, 0, strpos($admin_css, '/* compiled by scssphp'));
        }
        if ((strpos($_SERVER['SERVER_NAME'], $GLOBALS['stageurl']) || $_SERVER['SERVER_NAME'] == $GLOBALS['stageurl']) && strlen($stageuser) > 0) {
            $context = stream_context_create(array('http' => array('header' => 'Authorization: Basic ' . base64_encode("$stageuser:$stagepass"))));
            $compiled_css = file_get_contents($themeurl . '/sass/style.php?p=style.scss', false, $context);
            $compiled_admin_css = file_get_contents($themeurl . '/sass/style.php?p=admin.scss', false, $context);
            // echo('<pre>'); print_r($compiled_admin_css); echo('</pre>');
        } else {
            $compiled_css = file_get_contents($themeurl . '/sass/style.php?p=style.scss');
            $compiled_admin_css = file_get_contents($themeurl . '/sass/style.php?p=admin.scss');
        }
        $file = fopen($themedir . '/style.css', "r+");
        fwrite($file, $style_css . $compiled_css);
        fclose($file);
        $file = fopen($themedir . '/admin.css', "r+");
        fwrite($file, $admin_css . $compiled_admin_css);
        fclose($file);
    }
}


?>