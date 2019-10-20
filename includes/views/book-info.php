<?php
global $dw_books;
?>


<div class="wrap">
    <h1><?php _e('Book info', 'dw-books'); ?></h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php
                        $dw_books->books_info_object->prepare_items();
                        $dw_books->books_info_object->display(); ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
