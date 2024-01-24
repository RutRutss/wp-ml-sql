<?php

//แปลงลิงก์
function convert_to_youtube_link($originalLink)
    {
        // Extract video ID from the original link
        $videoID = get_youtube_video_id($originalLink);

        // Construct the embed link
        $embedLink = 'https://www.youtube.com/embed/' . $videoID;

        return $embedLink;
    }

    function get_youtube_video_id($link)
    {
        // Extract video ID from various YouTube link formats
        $patterns = [
            '/^https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/^https:\/\/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/^https:\/\/www\.youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $link, $matches)) {
                return $matches[1];
            }
        }

        // If no match found, return an empty string or handle accordingly
        return '';
    }

//บันทึกลงฐานข้อมูล
function save_contents_to_database($videoName, $videoDesc, $videoLink) {
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
}