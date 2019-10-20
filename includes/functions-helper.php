<?php
/**
 * Helper functions
 *
 * @package DW_Books/functions
 */

if (! function_exists('dumpit')) {
    function dumpit($var) {
        echo '<pre style="text-align: left; direction:ltr;">';
        var_dump($var);
        echo '</pre>';
    }
}

function get_info() {

}

function set_book_info($book_id, $info = []) {
    global $wpdb;

    $allowed_keys = ['isbn'];

    $info = array_filter($info, function($val, $key) use($allowed_keys) {
        return in_array($key, $allowed_keys);

    }, ARRAY_FILTER_USE_BOTH);

    $info_keys = implode(", ", array_keys($info));
    $info_values = implode(", ", array_values($info));

    $wpdb->query($wpdb->prepare("
        INSERT INTO `{$wpdb->prefix}books_info (post_id, {$info_keys}) VALUES ({$book_id}, {$info_values});
    "));
}

function get_book_info($book_id = 0, $key = '') {
    if (! $book_id) {
        global $post;
        $book_id = $post->ID;
    }
}
