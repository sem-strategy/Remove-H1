<?php
/*
Plugin Name: Remove H1
Description: Plugin removes H1 header with class page-title from selected pages.
Version: 1.5
Author: Sem Strategy
Author URI: https://sem-strategy.pl/
*/

// Page editor custom Field
function remove_h1_add_custom_box()
{
    $screens = ['post', 'page'];
    foreach ($screens as $screen) {
        add_meta_box(
            'remove_h1_box_id',                     // HTML element ID 
            esc_html__('Remove H1', 'remove-h1'),    // Displayed name
            'remove_h1_custom_box_html',            // function displaying box value
            $screen,                                // screen
            'side',                                 // display in sidebar
            'high'
        );
    }
}
add_action('add_meta_boxes', 'remove_h1_add_custom_box');

function remove_h1_custom_box_html($post)
{
    $value = get_post_meta($post->ID, '_remove_h1_meta_key', true);
?>
    <label for="remove_h1_field">Czy usunąć nagłowek H1:</label><br />
    <input type="checkbox" name="remove_h1_field" value="1" <?php checked($value, '1'); ?> />
    <?php
}

// store custom value
function remove_h1_save_postdata($post_id)
{
    if (array_key_exists('remove_h1_field', $_POST)) {
        $checkbox_value = $_POST['remove_h1_field'] ? '1' : '0';
        update_post_meta(
            $post_id,
            '_remove_h1_meta_key',
            $_POST['remove_h1_field']
        );
    }
}
add_action('save_post', 'remove_h1_save_postdata');

function remove_h1_script()
{
    if (is_single() || is_page()) {
        $post_id = get_the_ID();
        $value = get_post_meta($post_id, '_remove_h1_meta_key', true);
        if ($value == 1) { ?>
            <script type="text/javascript" id="remove-h1">
                document.addEventListener("DOMContentLoaded", function() {
                        var h1 = document.querySelector('h1.page-title');
                        h1.parentNode.removeChild(h1);
                    });
            </script>
<?php }
    }
}
add_action('wp_head', 'remove_h1_script', 99);
