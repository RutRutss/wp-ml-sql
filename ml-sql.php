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


//เพิ่มเมนูฝั่งแอดมิน
function ml_sql_settings_page()
{
    add_menu_page('ML-SQL Settings', 'ML-SQL', 'manage_options', 'ml-sql-settings', 'ml_sql_settings_content');
}

//เรียกหน้าเพิ่มวิดีโอฝั่งแอดมิน
function ml_sql_settings_content()
{
?>
    <div class="wrap">
        <h1>ML-SQL Settings</h1>
        <?php
        // Include the admin template
        include(plugin_dir_path(__FILE__) . 'admin/add_videos.php');
        ?>
    </div>
<?php
}

add_action('admin_menu', 'ml_sql_settings_page');

function ml_sql_display_data_shortcode()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'ml_sql';

    $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    ob_start();
?>
    <div class="ml-sql-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Video Name</th>
                    <th>Video Description</th>
                    <th>Video Link</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row) : ?>

                    <!-- show Card -->
                    <div class="card">
                        <div class="card-header" id="heading<?php echo esc_html($row['id']); ?>" data-toggle="collapse" data-target="#collapse<?php echo esc_html($row['id']); ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_html($row['id']); ?>">
                            <h5 class="mb-0">
                                <button class="btn">
                                    <?php echo esc_html($row['videoName']); ?>
                                </button>
                            </h5>
                        </div>

                        <div id="collapse<?php echo esc_html($row['id']); ?>" class="collapse" aria-labelledby="heading<?php echo esc_html($row['id']); ?>" data-parent="#accordion">
                            <div class="card-body">
                                <p><strong></strong> <?php echo esc_html($row['videoDesc']); ?></p>


                                <div class="embed-responsive embed-responsive-16by9 text-center">
                                    <style>
                                        @media (max-width: 767px) {

                                            /* ถ้าขนาดหน้าจอเป็นมือถือ */
                                            #youtubeemb {
                                                width: 100% !important;
                                                height: 300px !important;
                                            }
                                        }

                                        @media (min-width: 768px) {

                                            /* ถ้าขนาดหน้าจอไม่ใช่มือถือ (Desktop, Tablet, ...) */
                                            #youtubeemb {
                                                width: 70%;
                                                height: 480px !important;
                                            }
                                        }
                                    </style>

                                    <iframe class="embed-responsive-item" id="youtubeemb" src="<?php echo esc_url($row['videoLink']); ?>" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
    return ob_get_clean();
}

add_shortcode('ml_sql_display_data', 'ml_sql_display_data_shortcode');
