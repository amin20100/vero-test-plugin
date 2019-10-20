<?php
/**
 * Book class
 */
class DW_Book
{
    /**
     * Book post id
     *
     * @var int
     */
    public $id = 0;

    /**
     * Class constructor
     */
    public function __construct($post_id = 0) {
        if ($post_id) {
            $this->id = $post_id;
            // $this->setup();
        }
    }

    /**
     * Get book information
     *
     * @param string $key -- for now it only supports `isbn`
     */
    public function get_info($key = '') {
        global $wpdb;

        $results = $wpdb->get_results("
            SELECT * FROM {$wpdb->prefix}books_info WHERE post_id = {$this->id}
        ");

        $arr = [];

        $allowed_keys = ['isbn'];

        foreach ($results as $row) {
            foreach ($allowed_keys as $k) {
                $filtered = wp_list_pluck($results, 'isbn');
                $arr[$k] = $filtered[0];
            }
        }

        if ($key) return array_key_exists($key, $arr) ? $arr[$key] : '';

        return $arr;
    }

    /**
     * It is considered to be extended in future such that the `books_info` table won't just have a isbn column and be some thing like this:
     *       - Books_info
     *           - ID
     *           - post_id
     *           - isbn
     *           - year
     *           - translator
     *           - and so on...
     *
     * @param array $info -- key value sets of information about books, for now it only supports ['isbn' => 'xxxx']
     */
    public function attach_info($info = []) {
        if (! $this->id) return false;

        global $wpdb;

        $allowed_keys = ['isbn'];

        $info = array_filter($info, function($key) use($allowed_keys) {
            return in_array($key, $allowed_keys);

        }, ARRAY_FILTER_USE_KEY);



        if (! $info) return false;

        if ($this->get_info()) {
            array_walk($info, function(&$v, $i) use($wpdb) {
                $v = $wpdb->prepare('%s', $v);
                $v = "$i = $v";
            });

            $update_pairs = implode(", ", $info);
            $wpdb->query($wpdb->prepare("
                UPDATE `{$wpdb->prefix}books_info` SET {$update_pairs} WHERE post_id = %d;

            ", $this->id));

        } else {
            $info_keys = implode(", ", array_keys($info));
            $info_values = implode(", ", array_values($info));

            $wpdb->query($wpdb->prepare("
                INSERT INTO `{$wpdb->prefix}books_info` (post_id, {$info_keys}) VALUES (%d, %s);

            ", $this->id, $info_values));
        }

    }
}
