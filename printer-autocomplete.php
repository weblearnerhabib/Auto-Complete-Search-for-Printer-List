<?php
/*
 * Plugin Name:       Auto Complete Search for Printer List
 * Plugin URI:        https://github.com/weblearnerhabib/
 * Description:       Ability to autocomplete from Backend Data in Search Field.
 * Version:           1.1.2
 * Requires at least: 5.3
 * Requires PHP:      7.2
 * Author:            Freelancer Habib
 * Author URI:        https://freelancer.com/u/csehabiburr183/
 * Text Domain:       acsfl
 */

// Enqueue scripts and styles
function enqueue_autocomplete_scripts() {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
}

add_action('wp_enqueue_scripts', 'enqueue_autocomplete_scripts');

// Add administration menu
function printer_autocomplete_menu() {
    add_menu_page(
        'Printer Autocomplete Settings',
        'Printer Autocomplete',
        'manage_options',
        'printer-autocomplete-settings',
        'printer_autocomplete_settings_page'
    );
}

add_action('admin_menu', 'printer_autocomplete_menu');

// Settings page
function printer_autocomplete_settings_page() {
    ?>
    <div class="wrap">
        <h2>Printer Autocomplete Settings</h2>
        <form method="post" action="">
            <?php
            if (isset($_POST['printer_names'])) {
                $printer_names = sanitize_textarea_field($_POST['printer_names']);
                update_option('printer_autocomplete_names', $printer_names);
                echo '<div class="updated"><p>Printer names updated!</p></div>';
            }

            $current_names = get_option('printer_autocomplete_names', '');
            ?>
            <label for="printer_names">Enter Printer Names (comma-separated):</label>
            <textarea id="printer_names" name="printer_names" style="width: 100%;"><?php echo esc_textarea($current_names); ?></textarea>
            <p class="description">Enter printer names separated by commas.</p>
            <input type="submit" class="button button-primary" value="Save Names">
        </form>
    </div>
    <?php
}



// Add autocomplete to the frontend
function add_autocomplete_to_frontend() {
    $printer_names = get_option('printer_autocomplete_names', '');
    $printer_names_array = explode(',', $printer_names);

    wp_enqueue_script('printer-autocomplete', plugin_dir_url(__FILE__) . 'printer-autocomplete.js', array('jquery', 'jquery-ui-autocomplete'), '1.0', true);
    wp_localize_script('printer-autocomplete', 'printerAutocompleteData', $printer_names_array);
}

add_action('wp_enqueue_scripts', 'add_autocomplete_to_frontend');

// Shortcode for adding the search field
function printer_autocomplete_shortcode() {
    ob_start();
    ?>
    <input type="text" id="printerSearch" placeholder="Search printers">
    <?php
    return ob_get_clean();
}

add_shortcode('printer_search', 'printer_autocomplete_shortcode');
