<?php
// Lấy 9 truyện đã hoàn thành
$truyen_query = new WP_Query([
    'post_type'      => 'truyen_chu',
    'posts_per_page' => 9,
    'orderby'        => 'rand',
    'order'          => 'DESC',
    'tax_query'      => [
        [
            'taxonomy' => 'trang_thai',
            'field'    => 'slug',
            'terms'    => 'da-hoan-thanh'
        ]
    ]
]);

if ($truyen_query->have_posts()) : ?>
    <section class="section-slider py-5">
        <div class="section-title"><span>Truyện đã hoàn thành</span></div>
        <div class="swiper swiper-latest">
            <div class="swiper-wrapper">
                <?php while ($truyen_query->have_posts()) : $truyen_query->the_post(); ?>
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
<?php endif; 
wp_reset_postdata();
?>