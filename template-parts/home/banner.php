<?php
/**
 * Template part for displaying banner on homepage
 *
 * @package commicpro
 */

// Debug: Check if we're on the right page
echo '<!-- Debug: Current page is ' . (is_front_page() ? 'front page' : 'not front page') . ' -->';

// Get posts from truyen-chu post type
$args = array(
    'post_type' => 'truyen_chu',
    'posts_per_page' => 9,
    'orderby' => 'date',
    'order' => 'DESC'
);

$query = new WP_Query($args);

// Debug: Check query results
echo '<!-- Debug: Found ' . $query->found_posts . ' posts -->';

if ($query->have_posts()) :
?>
<section class="section-hero-banner py-5">
    <div class="bg" style="background-image: url('<?php echo get_theme_file_uri('assets/images/banner-bg.jpg'); ?>');">
        <div class="bg-overlay"></div>
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-none d-md-block">
                    <div class="swiper swiper-story-content swiper-fade">
                        <div class="swiper-wrapper">
                            <?php
                            while ($query->have_posts()) :
                                $query->the_post();
                                ?>
                                <div class="swiper-slide">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <h3 class="fs-2 text-capitalize font-secondary-title"><?php the_title(); ?></h3>
                                    </a>
                                    <div class="synopsis line-clamp line-clamp-6">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="custom-btn mt-3">
                                        <span><?php esc_html_e('Đọc Truyện', 'commicpro'); ?></span>
                                    </a>

                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="overflow-hidden">
                    <div class="swiper swiper-story-thumb swiper-coverflow swiper-3d">
                        <div class="swiper-wrapper">
                            <?php
                            $query->rewind_posts();
                            while ($query->have_posts()) :
                                $query->the_post();
                                ?>
                                <div class="swiper-slide" data-url="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="d-block swiper-slide-a ratio ratio-3x4 rounded-4 overflow-hidden">
                                        <?php the_post_thumbnail('medium', array('class' => 'attachment-medium size-medium wp-post-image')); ?>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="d-block d-md-none">
                    <div class="swiper swiper-story-content swiper-fade">
                        <div class="swiper-wrapper">
                            <?php
                            while ($query->have_posts()) :
                                $query->the_post();
                                ?>
                                <div class="swiper-slide">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <h3 class="fs-2 text-capitalize font-secondary-title"><?php the_title(); ?></h3>
                                    </a>
                                    <div class="synopsis line-clamp line-clamp-3">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="custom-btn mt-3">
                                        <span><?php esc_html_e('Đọc Truyện', 'commicpro'); ?></span>
                                    </a>

                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
else:
    // Debug: Show message if no posts found
    echo '<!-- Debug: No posts found in truyen_chu post type -->';
endif;
wp_reset_postdata(); 