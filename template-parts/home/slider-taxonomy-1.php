<?php
// template-parts/home/slider-taxonomy-1.php
$terms = get_terms([
    'taxonomy' => 'the_loai',
    'hide_empty' => true,
    'orderby' => 'rand',
    'number' => 3
]);
if (!empty($terms) && !is_wp_error($terms)) :
    $term = $terms[0];
    $tax_query = new WP_Query([
        'post_type' => 'truyen_chu',
        'posts_per_page' => 9,
        'tax_query' => [[
            'taxonomy' => 'the_loai',
            'field' => 'term_id',
            'terms' => $term->term_id
        ]]
    ]);
    if ($tax_query->have_posts()) : ?>
<section class="section-slider py-5">
    <div class="section-title"><span><?php echo esc_html($term->name); ?></span> <a href="<?php echo get_term_link($term); ?>">Xem thêm >></a></div>
    <div class="swiper swiper-tax1">
        <div class="swiper-wrapper">
            <?php while ($tax_query->have_posts()) : $tax_query->the_post(); ?>
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
<?php endif; wp_reset_postdata(); endif; ?>
