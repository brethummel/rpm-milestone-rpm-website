<?php

$version = '1.0.0';

// ======================================= //
//               THEME INFO                //
// ======================================= // 

$theme = wp_get_theme();
$GLOBALS['themename'] = get_stylesheet();
$GLOBALS['themeversion'] = $theme->get('Version');


// ======================================= //
//            CHECK FOR UPDATE             //
// ======================================= // 

function spr_check_for_update($transient) {
	$themename = $GLOBALS['themename'];
	if ($transient != null && !isset($transient->response[$themename])) {
		// echo("<pre>Theme Updater ran because no response was stored in the transient...</pre>");
		$server = 'https://updates.wp-springboard.com/api/latest.php';
		$curl = curl_init($server);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$json = curl_exec($curl);
		if ($e = curl_error($curl)) {
			echo($e);
			return false;
		} else {
			$response = json_decode($json, true);
			if (is_array($response)) {
				$springboard = array(
					'theme'       => $themename,
					'new_version' => $response['update']['new_version'],
					'url'         => $response['update']['url'],
					'package'     => $response['update']['package'],
				);
				$themenewversion = $response['update']['new_version'];
				if (isset($themenewversion) && $themenewversion != $GLOBALS['themeversion']) {
					$GLOBALS['themenewversion'] = $themenewversion;
					$transient->response[$themename] = $springboard;
				} else {
					$springboard['requires'] = '';
					$springboard['requires_php'] = '';
					$transient->no_update[$themename] = $springboard;
				}
				return $transient;
			} else {
				return false;
			}
			
		}
		curl_close($curl);
	}
}
add_filter('pre_set_site_transient_update_themes', 'spr_check_for_update', 10, 2);


// ======================================= //
//             CHECK PACKAGE               //
// ======================================= // 

function spr_check_package($reply, $package) {
	// echo("<pre>Theme Updater checked updates.wp-springboard.com/update/springboard...</pre>");
	if (strpos($package, 'updates.wp-springboard.com/update/springboard') !== false) {
		$GLOBALS['spr_update_flag'] = true;
		// exit('The package came from springboard! With: ' . $package . ' And: ' . $GLOBALS['spr_update_flag']);
	}
	return $reply;
}
add_filter('upgrader_pre_download', 'spr_check_package', 10, 2);


// ======================================= //
//           BACKUP THEME FILES            //
// ======================================= // 

function spr_theme_backup($response) { // backup existing theme files
	if (isset($GLOBALS['spr_update_flag']) && $GLOBALS['spr_update_flag']) {
		$themename = $GLOBALS['themename'];
		$themedir = get_template_directory();
		$root = ABSPATH;
		$source = $themedir;
		$destination = $root . 'wp-content/upgrade/' . $themename . '-bu';
		spr_recursive_copy($source, $destination);
	}
	return $response;
}
add_filter('upgrader_pre_install', 'spr_theme_backup', 10, 2);


// ======================================= //
//    RENAME INSTALL FILE TO THEME NAME    //
// ======================================= // 

function spr_rename_source($source) { // rename expanded intall file to theme name
	if (isset($GLOBALS['spr_update_flag']) && $GLOBALS['spr_update_flag']) {
		$themeurl = get_template_directory_uri();
		$themename = $GLOBALS['themename'];
		$themedir = get_template_directory();
		$root = ABSPATH;
		rename($source, $root . 'wp-content/upgrade/' . $themename);
		$source = $root . 'wp-content/upgrade/' . $themename;
	}
	return $source;
}
add_filter( 'upgrader_source_selection', 'spr_rename_source', 10, 4 );


// ======================================= //
//        RECOVER UNCHANGED FILES          //
// ======================================= // 

function spr_theme_recover($response) { // recover theme files that do not exist in update
	if (isset($GLOBALS['spr_update_flag']) && $GLOBALS['spr_update_flag']) {
		$themeurl = get_template_directory_uri();
		$themename = $GLOBALS['themename'];
		$themeversion = $GLOBALS['themeversion'];
		$themedir = get_template_directory();
		$root = ABSPATH;
		$source = $root . 'wp-content/upgrade/' . $themename . '-bu';
		$destination = $themedir;
		spr_recursive_copy($source, $destination);
		spr_update_css($themeurl, $themedir, $GLOBALS['stageuser'], $GLOBALS['stagepass'], true);
		spr_recursive_delete($source);
		spr_recursive_delete($root . 'wp-content/upgrade/' . $themename);
		$transient = get_site_transient('update_themes');
		$themenewversion = $transient->response[$themename]['new_version'];
		spr_update_theme_version($themeversion, $themenewversion);
	}
	return $response;
}
add_filter('upgrader_post_install', 'spr_theme_recover', 10, 2);


// ======================================= //
//          UPDATE THEME VERSION           //
// ======================================= // 

function spr_update_theme_version($themeversion, $themenewversion) {
	$themedir = get_template_directory();
	$style_css = file_get_contents($themedir . '/style.css');
	$new_css = str_replace($themeversion, $themenewversion, $style_css);
	$file = fopen($themedir . '/style.css', "r+");
	fwrite($file, $new_css);
	fclose($file);
	set_site_transient('update_themes', null);
	if (function_exists('wp_clean_update_cache')) {
		wp_clean_update_cache();
	}
	if (function_exists('rocket_clean_domain')) {
		rocket_clean_domain();
	}
	unset($GLOBALS['spr_update_flag']);
}


// ======================================= //
//                UTILITIES                //
// ======================================= // 

function spr_recursive_copy($source, $destination) {
    if (!file_exists($destination)) {
        mkdir($destination);
    }
	$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
    foreach ($files as $fullPath => $file) {
        $path = str_replace($source, "", $file->getPathname()); //get relative path of source file or folder
        if ($file->isDir() && !file_exists($destination . "/" . $path)) {
            mkdir($destination . "/" . $path);
        } elseif (!file_exists($destination . "/" . $path)) {
        	copy($fullPath, $destination . "/" . $path);
		}
    }
}

function spr_recursive_delete($directory) {
	$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
		$todo = ($file->isDir() ? 'rmdir' : 'unlink');
		$todo($file->getRealPath());
	}
	rmdir($directory);
}

?>