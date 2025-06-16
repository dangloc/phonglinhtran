<?php
// Lấy 9 truyện đã hoàn thành
$latest_query = new WP_Query([
    'post_type' => 'truyen_chu',
    'posts_per_page' => 6,
    'orderby' => 'date',
    'order' => 'DESC'
]);

if ($latest_query->have_posts()) : ?>
    <section class="section-slider pb-5">
        <div class="section-title"><span>Truyện mới cập nhật</span> </div>
        <div class="swiper swiper-latest">
            <div class="swiper-wrapper">
                <?php while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
                    <div class="swiper-slide" data-truyen-id="<?php echo get_the_ID(); ?>">
                        <a href="<?php the_permalink(); ?>">
                            <?php 
                            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            ?>
                            <img src="<?php echo $featured_img_url ?>" 
                                alt="<?php the_title_attribute(); ?>" 
                                onerror="this.src='<?php echo get_template_directory_uri(); ?>/assets/images/icon-book.png'"
                            />
                            <div class="slide-title"><?php the_title(); ?></div>
                                <?php 
                            $trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
                            $is_completed = false;
                            if ($trang_thai && !is_wp_error($trang_thai)) {
                                foreach ($trang_thai as $term) {
                                    if ($term->slug === 'da-hoan-thanh') {
                                        $is_completed = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            <p class="count-port">
                                <?php
                                if($is_completed){
                                    ?>
                                    Full
                                    <?php
                                }else{
                                    ?>
                                    <small class="text-muted-custom chapter-count" data-truyen-id="<?php echo get_the_ID(); ?>">
                                        <?php echo $is_completed ? 'Full' : '...'; ?>
                                    </small>
                                    <?php
                                }
                                ?>
                            </p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; 
wp_reset_postdata();
?>