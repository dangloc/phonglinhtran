<?php
/**
 * The template for displaying truyện chữ archive
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <header class="page-header mb-4">
            <h2 class="page-title"><?php echo esc_html__('Tất cả truyện', 'commicpro'); ?></h2>
        </header>

        <div class="row">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                   <?php get_template_part( 'template-parts/home/item-card' ); ?>
                <?php endwhile; ?>

                <div class="col-12">
                    <?php custom_pagination(); ?>
                </div>

            <?php else : ?>
                <div class="col-12">
                    <p><?php esc_html_e('Không tìm thấy truyện nào.', 'commicpro'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer(); 