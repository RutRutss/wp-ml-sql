<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบและบันทึกข้อมูลเมื่อฟอร์มถูกส่งมา
    $videoName = sanitize_text_field($_POST["videoName"]);
    $videoDesc = sanitize_textarea_field($_POST["videoDesc"]);
    $videoLink = esc_url_raw($_POST["videoLink"]);

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
}
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title">Add Video</h1>
            </header>

            <div class="entry-content">
                <!-- แสดงฟอร์ม -->
                <form method="post" action="">
                    <label for="videoName">Video Name:</label>
                    <input type="text" name="videoName" required>

                    <label for="videoDesc">Video Description:</label>
                    <textarea name="videoDesc" required></textarea>

                    <label for="videoLink">Video Link:</label>
                    <input type="url" name="videoLink" required>

                    <input type="submit" value="Submit">
                </form>
            </div>

        </article>

    </main>
</div>