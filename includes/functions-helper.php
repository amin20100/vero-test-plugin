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

/**
 * Insert a value or key/value pair after a specific key in an array.  If key doesn't exist, value is appended
 * to the end of the array.
 *
 * @param array $array
 * @param string $key
 * @param array $new
 *
 * @return array
 */
if (! function_exists('array_insert_after')) {
    function array_insert_after( &$array, $key, $new ) {
        $keys = array_keys( $array );
        $index = array_search( $key, $keys );
        $pos = false === $index ? count( $array ) : $index + 1;
        $array = array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
        return $array;
    }
}
