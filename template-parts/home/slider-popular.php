<?php
// template-parts/home/slider-popular.php
$popular_query = new WP_Query([
    'post_type' => 'truyen_chu',
    'posts_per_page' => 10,
    'meta_key' => 'view_count', // Đúng tên field ACF
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
]);
if ($popular_query->have_posts()) : ?>
<section class="section-slider py-5">
    <div class="section-title"><span>Truyện xem nhiều</span> <a href="<?php echo get_post_type_archive_link('truyen_chu'); ?>">Xem thêm >></a></div>
    <div class="swiper swiper-popular">
        <div class="swiper-wrapper">
            <?php while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
                <div class="swiper-slide">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                        <div class="slide-title"><?php the_title(); ?></div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>
<?php endif; wp_reset_postdata(); ?>