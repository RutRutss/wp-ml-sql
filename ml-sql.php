<?php
/*
Plugin Name: MicroLearning SQL
Plugin URI: 
Description: MicroLearning SQL
Author: Wisarut Yuensuk
Version: 1.0
Author URI: 
*/


register_activation_hook(__FILE__, 'ml_sql_activate');

function ml_sql_activate()
{
    // ตรวจสอบว่าตาราง ml_sql ยังไม่มีอยู่
    if (get_option('ml_sql_db_version') === false) {
        ml_sql_create_table();
    }
}
//สร้างตารางเก็บข้อมูลวิดีโอ
function ml_sql_create_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'ml_sql';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        videoName varchar(255) NOT NULL,
        videoDesc text NOT NULL,
        videoLink varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // บันทึกเวอร์ชันฐานข้อมูล
    add_option('ml_sql_db_version', '1.0');
}

// เพิ่ม Bootstrap ให้ปลั๊กอิน
add_action('wp_enqueue_scripts', 'ml_sql_enqueue');
function ml_sql_enqueue()
{
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');
    wp_enqueue_style('ml-sql-css', plugin_dir_url(__FILE__) . 'css/style.css', array(), '1.0', 'all');
    wp_enqueue_style('google-fonts-css', 'https://fonts.googleapis.com/css2?family=Kanit:wght@500&display=swap');
    wp_enqueue_script('bootstrapcdn-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array('jquery'), true);
}



// เพิ่มเมนูฝั่งแอดมิน
function ml_sql_settings_page()
{
    add_menu_page('ML-SQL', 'ML-SQL', 'manage_options', 'ml-sql-settings', 'display_admin_ml_sql_page');
}

//เรียกหน้าเพิ่มวิดีโอฝั่งแอดมิน
function display_admin_ml_sql_page()
{
    // Include the admin template
    include(plugin_dir_path(__FILE__) . 'admin/ml_sql_admin.php');
}

add_action('admin_menu', 'ml_sql_settings_page');

function display_accordion_videos_block()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ml_sql';
    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
    ob_start();
    include(plugin_dir_path(__FILE__) . 'blocks/accordion_videos_block.php');
    return ob_get_clean();
}

add_shortcode('accordion_videos', 'display_accordion_videos_block');

// Register the admin post endpoint for deleting
add_action('admin_post_ml_sql_delete', 'ml_sql_handle_delete');
function ml_sql_handle_delete()
{
    // Check nonce
    check_admin_referer('ml_sql_delete_nonce', '_wpnonce');

    // Get the ID from the request
    $video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Perform the delete operation (adjust based on your needs)
    global $wpdb;
    $table_name = $wpdb->prefix . 'ml_sql';
    $wpdb->delete($table_name, array('id' => $video_id));

    // Redirect after deletion
    wp_redirect(admin_url('admin.php?page=ml-sql-settings'));
    exit();
}
