<?php
function ds_enqueue_script() {
    wp_enqueue_style('main-style',get_stylesheet_url());

}
add_action('wp_emqueue_script'.'ds_enqueue_script')

?>