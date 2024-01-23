<?php
wp_head();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบและบันทึกข้อมูลเมื่อฟอร์มถูกส่งมา
    $videoName = sanitize_text_field($_POST["videoName"]);
    $videoDesc = sanitize_textarea_field($_POST["videoDesc"]);
    $videoLink = esc_url_raw($_POST["videoLink"]);

    // ตรวจสอบว่าเป็นลิงก์ Youtube.com หรือไม่
    $parsed_url = parse_url($videoLink);

    if ($parsed_url && isset($parsed_url['host']) && $parsed_url['host'] === 'www.youtube.com') {
        // ตรวจสอบรูปแบบของลิงก์
        if (preg_match('/^https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)&/', $videoLink, $matches)) {
            $videoId = $matches[1];

            // สร้างลิงก์ใหม่
            $videoLink = "https://www.youtube.com/embed/{$videoId}?si=OkLxKzy_RjpuxHbS";

            global $wpdb;
            $table_name = $wpdb->prefix . 'ml_sql';

            $wpdb->insert(
                $table_name,
                array(
                    'videoName' => $videoName,
                    'videoDesc' => $videoDesc,
                    'videoLink' => $videoLink,
                )
            );
            echo "บันทึกสำเร็จ";
        } else {
            // ลิงก์ไม่ได้ตรงกับรูปแบบที่ต้องการ
            echo "ลิงก์ไม่ถูกต้อง";
        }
    } else {
        // ไม่ใช่ลิงก์ Youtube.com
        echo "ไม่ใช่ลิงก์ Youtube.com";
    }
}
?>

<div id="primary" class="content-area ml-sql-font">
    <main id="main" class="site-main" role="main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header text-center">
                <h1 class="entry-title">เพิ่มวิดีโอการสอน</h1>
            </header>

            <div class="container mb-3">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <div class="entry-content">
                            <!-- แสดงฟอร์ม -->
                            <form method="post" action="">
                                <div class="mb-3">
                                    <label for="videoName" class="form-label">ชื่อวิดีโอ:</label>
                                    <input type="text" name="videoName" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="videoDesc" class="form-label">รายละเอียด:</label>
                                    <textarea name="videoDesc" class="form-control" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="videoLink" class="form-label">ลิงก์ยูทูป:</label>
                                    <input type="text" name="videoLink" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary">เพิ่มวิดีโอ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </article>

        <div class="container mt-3">
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'ml_sql';

            $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id", ARRAY_A);

            if (!empty($results)) {
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Video Name</th>';
                echo '<th>Video Description</th>';
                echo '<th>Video Link</th>';
                echo '<th>Delete</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                foreach ($results as $row) {
                    echo '<tr>';
                    echo '<td>' . esc_html($row['videoName']) . '</td>';
                    echo '<td>' . esc_html($row['videoDesc']) . '</td>';
                    echo '<td><a href="' . esc_url($row['videoLink']) . '" target="_blank">Watch Video</a></td>';
                    echo '<td><a href="' . esc_url(admin_url('admin-post.php?action=ml_sql_delete&id=' . $row['id'] . '&_wpnonce=' . wp_create_nonce('ml_sql_delete_nonce'))) . '" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a></td>';


                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo 'No data available.';
            }

            ?>
        </div>


    </main>
</div>
<?php wp_footer() ?>