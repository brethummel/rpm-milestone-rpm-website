<?php

$version = '1.0.0';

// echo('<pre>'); print_r('ui_utilities.php loaded!'); echo('</pre>');

// ======================================= //
//            PAGES REDIRECTS              //
// ======================================= //

// redirect to pages after login
function spr_login_redirect($url) {
    // echo('<pre>'); print_r('redirect triggered!'); echo('</pre>');
    global $current_user; 
    if (is_array($current_user->roles)) { // is there a user ?
        if (class_exists('NestedPages')) {
            $url = admin_url('admin.php?page=nestedpages');
        } else {
            $url = admin_url('edit.php?post_type=page');
        }
        return $url;
    }
}
add_filter('login_redirect', 'spr_login_redirect');  

// redirect dashboard clicks to pages list
function spr_dashboard_redirect(){
    if (class_exists('NestedPages')) {
        $url = admin_url('admin.php?page=nestedpages');
    } else {
        $url = admin_url('edit.php?post_type=page');
    }
    wp_redirect($url);
}
add_action('load-index.php','spr_dashboard_redirect');


// ======================================= //
//              ACF UTILITES               //
// ======================================= // 

add_filter('acf-autosize/wysiwyg/min-height', function() {
    return 100;
});

function spr_format_field($field, $post_id) { 
    $field = wptexturize($field);
    return $field;
}
add_filter('acf/load_value/type=text', 'spr_format_field', 10, 2 );


// ======================================= //
//             FILE MANAGEMENT             //
// ======================================= // 

// gets file list with optional filter
function spr_get_file_list($dir, $filter=false) {
    $directory = new RecursiveDirectoryIterator($dir);
    $files = new RecursiveIteratorIterator($directory);
    if ($filter) {
        $files = new RegexIterator($files, $filter);
    }
    $inventory = [];
    foreach ($files as $file) {
        $inventory[] = $file->getPathname();
    }
    return $inventory;
}

// checks whole sprinboard directory for new/removed files
function spr_has_new_files() {
    $themedir = get_template_directory();
    $sprpath = $GLOBALS['springboard_path'];
    $spr_files = spr_get_file_list($themedir . $sprpath, '/^(?!.*\/\.\.?$).*$/');
    // echo('<pre>'); print_r($spr_files); echo('</pre>');
    $transient = get_transient('spr_files_list');
    $lockfile = $themedir . '/spr_file_inventory.lock';
    if ($transient) {
        $changes = array_merge(array_diff($spr_files, $transient), array_diff($transient, $spr_files));
        foreach ($changes as $c => $change) {
            if (str_contains($change, '.lock')) { // overlook temporary lock files
                unset($changes[$c]);
            }
        }
        if (count($changes) == 0) {
            return false;
        } else {
            $message = 'File inventory changes detected';
            $message = [$message, PHP_EOL . '---> ' . implode(PHP_EOL . '---> ', $changes)];
            spr_write_to_debug_log(__FILE__, __LINE__ + 1, $message, false);
            if (!file_exists($lockfile)) {
                $myprocid = getmypid();
                $file = fopen($lockfile, 'w');
                fwrite($file, $myprocid);
                fclose($file);
            }
            return array(
                'spr_files' => $spr_files,
                'changes' => $changes, 
                'lockfile' => $lockfile
            );
        }
    } else {
        if (!file_exists($lockfile)) {
            $myprocid = getmypid();
            $file = fopen($lockfile, 'w');
            fwrite($file, $myprocid);
            fclose($file);
        }
        return array(
            'spr_files' => $spr_files, 
            'changes' => array(), 
            'lockfile' => $lockfile
        );
    }
}

function spr_capture_files_list($spr_file_changes) {
    $message = 'Writing Transient \'spr_files_list\'';
    $message = [$message, PHP_EOL . '---> ' . implode(PHP_EOL . '---> ', $spr_file_changes['changes'])];
    spr_write_to_debug_log(__FILE__, __LINE__ + 1, $message, true);
    set_transient('spr_files_list', $spr_file_changes['spr_files']);
    unlink($spr_file_changes['lockfile']);
}


// ======================================= //
//             OEMBED PREVIEW              //
// ======================================= // 

function spr_disable_author_in_preview($data) {
    // echo('<pre><p>boop 1!</p>'); print_r($data); echo('</pre>');
    unset($data['author_url']);
    $data['author_name'] = "Gordon Lightfoot";
    // unset($data['author_name']);
    // echo('<pre><p>boop 2!</p>'); print_r($data); echo('</pre>');
    return $data;
}
add_filter('oembed_response_data', 'spr_disable_author_in_preview', 10, 4);
add_filter('wpseo_meta_author', '__return_false');



// ======================================= //
//        PRIORITY METABOXES ORDER         //
// ======================================= // 
    
function spr_priority_metaboxes_order() {

    global $post;
    global $wp_meta_boxes;

    // echo('<pre>'); print_r($wp_meta_boxes); echo('</pre>');

    $posttype = $post->post_type;

    // this is defined in config.php
    $priority_metaboxes = $GLOBALS['priority_metaboxes'];

    if (array_key_exists($posttype, $priority_metaboxes) && function_exists('acf_add_local_field_group')) {

        // echo('<pre>'); echo("I think I'm in excerptposttypes!"); echo('</pre>');
        $metboxes = $wp_meta_boxes[$posttype]['acf_after_title']['high'];

        $sortedmetas = [];
        
        foreach ($priority_metaboxes[$posttype] as $box) {
            if (isset($metboxes[$box])) {
                $thisbox = $metboxes[$box];
                unset($metboxes[$box]);
                $sortedmetas[] = $thisbox;
            }
        }

        foreach($metboxes as $meta) {
            array_push($sortedmetas, $meta);
        }

        $wp_meta_boxes[$posttype]['acf_after_title']['high'] = $sortedmetas;

    }
}
add_action('acf/add_meta_boxes', 'spr_priority_metaboxes_order');



// ======================================= //
//             SIDEBAR ORDER               //
// ======================================= // 
    
function spr_priority_sidebars_order() {

    global $post;
    global $wp_meta_boxes;

    $posttype = $post->post_type;

    // this is defined in config.php
    $priority_sidebars = $GLOBALS['priority_sidebars'];

    if (array_key_exists($posttype, $priority_sidebars) && function_exists('acf_add_local_field_group')) {

        // echo('<pre>'); echo("I think I'm in excerptposttypes!"); echo('</pre>');
        $sidebar = $wp_meta_boxes[$posttype]['side']['core'];

        foreach ($sidebar as $s => $sbox) {
            if ($sbox['id'] == 'submitdiv') {
                $submitdiv = $sbox;
                unset($sidebar[$s]);
            }
        }

        $sortedmetas = array($submitdiv);
        
        foreach ($priority_sidebars[$posttype] as $box) {
            foreach ($sidebar as $s => $sbox) {
                if ($sbox['id'] == $box) {
                    $thisbox = $sbox;
                    unset($sidebar[$s]);
                $sortedmetas[] = $thisbox;
            }
        }
        }

        foreach($sidebar as $meta) {
            array_push($sortedmetas, $meta);
        }

        $wp_meta_boxes[$posttype]['side']['core'] = $sortedmetas;

    }
}
add_action('acf/add_meta_boxes', 'spr_priority_sidebars_order');



// ======================================= //
//           HIGHLIGHT MATCHES             //
// ======================================= // 

function spr_highlight_matches($haystack, $needles) {
    $match = false;
    if (gettype($needles) == 'array') {
        $haystackarr = explode(' ', $haystack);
        foreach ($needles as $term) {
            foreach ($haystackarr as $w => $word) {
                $start = -1;
                $wordlc = strtolower($word);
                $start = strpos($wordlc, strtolower($term));
                // echo('<pre>'); echo($start); echo('</pre>');
                if ($start > -1) {
                    $match = true;
                    $wordfront = substr($word, 0, $start);
                    $termmatch = '<strong>' . substr($word, $start, strlen($term)) . '</strong>';
                    $wordback = substr($word, $start + strlen($term));
                    $word = $wordfront . $termmatch . $wordback;
                    $haystackarr[$w] = $word;
                }
            }
            $haystack = implode(' ', $haystackarr);
        }
    }
    return array($match, $haystack);
}


// ======================================= //
//             debug.log WRITE             //
// ======================================= // 

function spr_write_to_debug_log($file, $line, $message, $incltrace=true) {
    $log = 'SPR LOG [' . basename($file) . ': ' . $line . ']: ';
    if (is_array($message)) {
        $log .= $message[0];
        $log .= $message[1];
    } else {
        $log .= $message;
    }
    if ($incltrace) {
        $log .=  PHP_EOL . (new Exception)->getTraceAsString();
    }
    if (!str_contains($log, '/wp-admin/admin-ajax.php')) {
        error_log($log);
    }
}


?>