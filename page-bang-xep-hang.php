<?php
/**
 * Template Name: Bảng xếp hạng
 */

get_header();
?>

<div class="container py-5">
            <h1 class="mb-4">Bảng xếp hạng truyện</h1>
            
            <?php
            // Lấy danh sách truyện theo đánh giá
            $args = array(
                'post_type' => 'truyen_chu',
                'posts_per_page' => 25,
                'meta_key' => 'rmp_rating_val_sum', // Meta key của plugin Rate My Post
                'orderby' => 'meta_value_num',
                'order' => 'DESC'
            );
            
            $ranked_stories = new WP_Query($args);
            
            if ($ranked_stories->have_posts()) : ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 50px">#</th>
                                <th scope="col">Truyện</th>
                                <th scope="col">Tác giả</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Đánh giá</th>
                                <th scope="col">Số lượt đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1;
                            while ($ranked_stories->have_posts()) : $ranked_stories->the_post();
                                // Lấy thông tin đánh giá
                                $rating_value = get_post_meta(get_the_ID(), 'rmp_rating_val_sum', true);
                                $votes_count = get_post_meta(get_the_ID(), 'rmp_vote_count', true);
                                
                                // Lấy tác giả
                                $tac_gia = get_the_terms(get_the_ID(), 'tac_gia');
                                $tac_gia_name = $tac_gia && !is_wp_error($tac_gia) ? $tac_gia[0]->name : 'Chưa cập nhật';

                                // Lấy trạng thái
                                $trang_thai = get_the_terms(get_the_ID(), 'trang_thai');
                                $trang_thai_name = $trang_thai && !is_wp_error($trang_thai) ? $trang_thai[0]->name : 'Chưa cập nhật';
                                $trang_thai_slug = $trang_thai && !is_wp_error($trang_thai) ? $trang_thai[0]->slug : '';
                            ?>
                                <tr>
                                    <td><?php echo $rank++; ?></td>
                                    <td>
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-non text-black">
                                            <?php the_title(); ?>
                                        </a>
                                    </td>
                                    <td><?php echo esc_html($tac_gia_name); ?></td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $trang_thai_slug === 'da-hoan-thanh' ? 'bg-success' : 
                                                ($trang_thai_slug === 'dang-tien-hanh' ? 'bg-primary' : 'bg-secondary'); 
                                        ?>">
                                            <?php echo esc_html($trang_thai_name); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            // Hiển thị sao đánh giá
                                            $rating = $rating_value / ($votes_count ? $votes_count : 1);
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= $rating) {
                                                    echo '<i class="fas fa-star text-warning"></i>';
                                                } else {
                                                    echo '<i class="far fa-star text-warning"></i>';
                                                }
                                            }
                                            ?>
                                            <span class="ms-2"><?php echo number_format($rating, 1); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($votes_count); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php
               
                
                wp_reset_postdata();
            else : ?>
                <div class="alert alert-info">
                    Chưa có truyện nào được đánh giá.
                </div>
            <?php endif; ?>

            <h3>Truyện đánh được giá cao</h3>
            <div class="card card-top-rate" style="width: fit-content">
              <?php echo do_shortcode('[ratemypost-top-rated]'); ?>
            </div>
</div>

<?php get_footer(); ?> 