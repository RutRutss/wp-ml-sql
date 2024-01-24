<div class="container-fluid">
    <div id="accordion">
        <?php foreach ($results as $row) : ?>


            <!-- show Card -->
            <div class="card">
                <div class="card-header video-accordion-header" id="heading<?php echo esc_html($row['id']); ?>" data-toggle="collapse" data-target="#collapse<?php echo esc_html($row['id']); ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_html($row['id']); ?>">
                    <h5 class="mb-0 p-2 video-accordion-title">
                            <?php echo esc_html($row['videoName']); ?>
                    </h5>
                </div>

                <div id="collapse<?php echo esc_html($row['id']); ?>" class="collapse video-accordion-content" aria-labelledby="heading<?php echo esc_html($row['id']); ?>" data-parent="#accordion">
                    <div class="card-body">
                        <p class="video-accordion-content-text"><strong></strong> <?php echo esc_html($row['videoDesc']); ?></p>


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
    </div>
</div>