<?php
/**
 * Post types, Taxonomies, Columns, taxonomies, ...
 *
 */

defined( 'ABSPATH' ) || exit;

class DW_Post_Types
{
	/**
	 * Hook in methods.
	 */
	public static function init() {
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
        add_action( 'add_meta_boxes', array( __CLASS__, 'metaboxes' ));
        add_action( 'save_post', [__CLASS__, 'save_book'] );

        self::manage_columns('book', [__CLASS__, 'manage_books_columns_titles']);
        add_action('manage_book_posts_custom_column' , [__CLASS__, 'manage_books_columns'], 10, 2);
    }

	/**
	 * Register core post types.
	 */
    public static function register_post_types() {

		if ( ! is_blog_installed() || post_type_exists( 'book' ) ) {
			return;
        }

        register_post_type(
			'book',
			apply_filters(
				'dw_books_register_books_post_types',
				array(
					'labels'              => array(
						'name'                  => __( 'Books', 'dw-books' ),
						'singular_name'         => __( 'Book', 'dw-books' ),
						'all_items'             => __( 'All Books', 'dw-books' ),
						'menu_name'             => _x( 'Books', 'Admin menu name', 'dw-books' ),
						'add_new'               => __( 'Add New', 'dw-books' ),
						'add_new_item'          => __( 'Add new Book', 'dw-books' ),
						'edit'                  => __( 'Edit', 'dw-books' ),
						'edit_item'             => __( 'Edit Book', 'dw-books' ),
						'new_item'              => __( 'New Book', 'dw-books' ),
						'view_item'             => __( 'View Book', 'dw-books' ),
						'view_items'            => __( 'View Books', 'dw-books' ),
						'search_items'          => __( 'Search Books', 'dw-books' ),
						'not_found'             => __( 'No Books found', 'dw-books' ),
						'not_found_in_trash'    => __( 'No Books found in trash', 'dw-books' ),
						'parent'                => __( 'Parent Book', 'dw-books' ),
						'featured_image'        => __( 'Book image', 'dw-books' ),
						'set_featured_image'    => __( 'Set Book image', 'dw-books' ),
						'remove_featured_image' => __( 'Remove Book image', 'dw-books' ),
						'use_featured_image'    => __( 'Use as Book image', 'dw-books' ),
						'insert_into_item'      => __( 'Insert into Book', 'dw-books' ),
						'uploaded_to_this_item' => __( 'Uploaded to this Book', 'dw-books' ),
						'filter_items_list'     => __( 'Filter Books', 'dw-books' ),
						'items_list_navigation' => __( 'Books navigation', 'dw-books' ),
						'items_list'            => __( 'Books list', 'dw-books' ),
					),
                    'description'         => __( 'This is where you can add new Books.', 'dw-books' ),
                    'menu_icon'           => 'dashicons-book-alt',
                    'menu_position'       => 5,
					'public'              => true,
					'show_ui'             => true,
					'publicly_queryable'  => true,
                    'exclude_from_search' => false,
                    'taxonomies'          => ['publisher', 'book_author'],
					'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
					'rewrite'             => array(
                        'slug'       => 'books',
						'with_front' => false,
						'feeds'      => true,
					),
					'query_var'           => true,
					'supports'            => ['title', 'editor', 'thumbnail', 'comments'],
					'has_archive'         => true,
					'show_in_nav_menus'   => true,
					'show_in_rest'        => true,
				)
			)
		);
    }

	/**
	 * Register core taxonomies.
	 */
    public static function register_taxonomies() {
		register_taxonomy(
			'publisher',
			apply_filters( 'dw_books_taxonomy_publisher_post_types', array( 'book' ) ),
			apply_filters(
				'dw_books_taxonomy_publisher',
				array(
                    'hierarchical'          => true,
                    'public'                => true,
                    'has-archive'           => true,
                    'publicly_queryable'    => true,
					'label'                 => __( 'Publishers', 'woocommerce' ),
					'labels'                => array(
						'name'              => __( 'Publishers', 'woocommerce' ),
						'singular_name'     => __( 'Publisher', 'woocommerce' ),
						'menu_name'         => _x( 'Publishers', 'Admin menu name', 'woocommerce' ),
						'search_items'      => __( 'Search Publishers', 'woocommerce' ),
						'all_items'         => __( 'All Publishers', 'woocommerce' ),
						'parent_item'       => __( 'Parent Publisher', 'woocommerce' ),
						'parent_item_colon' => __( 'Parent Publisher:', 'woocommerce' ),
						'edit_item'         => __( 'Edit Publisher', 'woocommerce' ),
						'update_item'       => __( 'Update Publisher', 'woocommerce' ),
						'add_new_item'      => __( 'Add new Publisher', 'woocommerce' ),
						'new_item_name'     => __( 'New Publisher name', 'woocommerce' ),
						'not_found'         => __( 'No Publishers found', 'woocommerce' ),
					),
					'show_ui'               => true,
					'query_var'             => true,
					'rewrite'               => array(
						'slug'         => 'publishers',
						'hierarchical' => true,
					),
				)
			)
        );

        register_taxonomy(
			'book_author',
			apply_filters( 'dw_books_taxonomy_Author_post_types', array( 'book' ) ),
			apply_filters(
				'dw_books_taxonomy_Author',
				array(
                    'hierarchical'          => true,
                    'public'                => true,
                    'has-archive'           => true,
                    'publicly_queryable'    => true,
					'label'                 => __( 'Authors', 'woocommerce' ),
					'labels'                => array(
						'name'              => __( 'Authors', 'woocommerce' ),
						'singular_name'     => __( 'Author', 'woocommerce' ),
						'menu_name'         => _x( 'Authors', 'Admin menu name', 'woocommerce' ),
						'search_items'      => __( 'Search Authors', 'woocommerce' ),
						'all_items'         => __( 'All Authors', 'woocommerce' ),
						'parent_item'       => __( 'Parent Author', 'woocommerce' ),
						'parent_item_colon' => __( 'Parent Author:', 'woocommerce' ),
						'edit_item'         => __( 'Edit Author', 'woocommerce' ),
						'update_item'       => __( 'Update Author', 'woocommerce' ),
						'add_new_item'      => __( 'Add new Author', 'woocommerce' ),
						'new_item_name'     => __( 'New Author name', 'woocommerce' ),
						'not_found'         => __( 'No Authors found', 'woocommerce' ),
					),
					'show_ui'               => true,
					'query_var'             => true,
					'rewrite'               => array(
						'slug'         => 'book-author',
						'hierarchical' => true,
					),
				)
			)
        );

        register_taxonomy_for_object_type('book_author', 'book');
        register_taxonomy_for_object_type('publisher', 'book');
    }

    public static function metaboxes() {
        add_meta_box( 'books-metabox', __( 'Book info', 'textdomain' ), [__CLASS__, 'books_metabox'], 'book' );
    }

    public static function books_metabox($post_item) {
        $book = new DW_Book($post_item->ID);
        echo '<label for="book_isbn">'. __('ISBN', 'dw-books') .'</label>';
        echo '<input id="book_isbn" type="text" name="isbn" value="'. $book->get_info('isbn') .'">';
    }

    public static function manage_columns($post_type, $callback = '', $priority = 10, $params = 1) {
        add_filter("manage_{$post_type}_posts_columns", $callback, $priority, $params);
    }

    public static function manage_books_columns_titles($columns) {
        array_insert_after($columns, 'title', ['isbn' => __('ISBN', 'dw-books')] );

        return $columns;
    }

    public static function manage_books_columns($column, $post_id) {
        $book = new DW_Book($post_id);

        switch ($column) {
            case 'isbn':
                echo $book->get_info('isbn');
                break;


        }
    }

    /**
     * Save book metabox
     */
    public static function save_book($post_id) {
        if (empty($_POST['post_type']) || $_POST['post_type'] !== 'book') return $post_id;

        if (!current_user_can("edit_post", $post_id)) return $post_id;

        if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) return $post_id;

        $info = [];

        if (! empty($_POST['isbn'])) {
            $info['isbn'] = sanitize_text_field($_POST['isbn']);
        }

        // Other info may go here

        if (! $info) return;

        $book = new DW_Book($post_id);
        $book->attach_info($info);
    }
}
