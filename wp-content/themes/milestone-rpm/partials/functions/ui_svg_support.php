<?php

$version = '1.0.0';

// ======================================= //
//             ADD SVG SUPPORT             //
// ======================================= // 

if ($allowsvgs) {
    add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
        global $wp_version;
        if ( $wp_version !== '4.7.1' ) {
            return $data;
        }
        $filetype = wp_check_filetype( $filename, $mimes );
        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename']
        ];
    }, 10, 4 );

    function spr_mime_types( $mimes ){
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter( 'upload_mimes', 'spr_mime_types' );

    function spr_fix_svg() {
        echo '';
    }
    add_action( 'admin_head', 'spr_fix_svg' );
}


?>