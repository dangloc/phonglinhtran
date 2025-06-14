<?php
/**
 * The template for displaying single chapter
 */

// Lấy thông tin truyện cha từ ACF field
$truyen = get_field('chuong_with_truyen');
$truyen_id = $truyen ? $truyen->ID : 0;

if (!$truyen_id) {
    wp_safe_redirect(home_url());
    exit;
}

// Lấy thông tin khóa chương và giá
$locked_from = get_post_meta($truyen_id, '_locked_from', true);
$chapter_price = get_post_meta($truyen_id, '_chapter_price', true);

// Lấy số thứ tự chương hiện tại
$args = array(
    'post_type' => 'chuong_truyen',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'chuong_with_truyen',
            'value' => $truyen_id,
            'compare' => '='
        )
    ),
    'orderby' => 'meta_value_num',
    'meta_key' => 'chapter_number',
    'order' => 'ASC'
);
$chapters = new WP_Query($args);
$current_chapter_number = 1;
$is_locked = false;
$current_post_id = get_the_ID();

if ($chapters->have_posts()) {
    while ($chapters->have_posts()) {
        $chapters->the_post();
        if (get_the_ID() == $current_post_id) {
            $is_locked = $locked_from && $current_chapter_number >= $locked_from;
            break;
        }
        $current_chapter_number++;
    }
}
wp_reset_postdata();

// Kiểm tra xem user đã mua chương này chưa bằng hàm mới
$user_id = get_current_user_id();
$is_purchased = can_user_read_chapter($user_id, $current_post_id, $truyen_id);

// Nếu chương bị khóa và chưa mua, chuyển hướng về trang truyện
if ($is_locked && !$is_purchased) {
    wp_safe_redirect(get_permalink($truyen_id));
    exit;
}

// Xử lý mua chương
if (isset($_POST['buy_chapter']) && wp_verify_nonce($_POST['buy_chapter_nonce'], 'buy_chapter_' . $current_post_id)) {
    $user_balance = get_user_meta($user_id, '_user_balance', true);
    
    if ($user_balance >= $chapter_price) {
        // Trừ tiền
        update_user_meta($user_id, '_user_balance', $user_balance - $chapter_price);
        
        // Thêm vào danh sách chương đã mua
        $purchased_chapters = get_user_meta($user_id, '_purchased_chapters', true);
        if (!is_array($purchased_chapters)) {
            $purchased_chapters = array();
        }
        $purchased_chapters[] = $current_post_id;
        update_user_meta($user_id, '_purchased_chapters', $purchased_chapters);
        
        // Refresh trang
        wp_safe_redirect(get_permalink());
        exit;
    } else {
        $error_message = 'Số dư không đủ để mua chương này!';
    }
}

// Get current chapter number and story ID from URL
$current_url = $_SERVER['REQUEST_URI'];
preg_match('/chuong-(\d+)-([^\/]+)/', $current_url, $matches);
$current_chapter = isset($matches[1]) ? intval($matches[1]) : 0;
$story_slug = isset($matches[2]) ? $matches[2] : '';

// Get next chapter
$next_chapter = $current_chapter + 1;
$next_chapter_url = home_url("/index.php/chuong/chuong-{$next_chapter}-{$story_slug}/");

// Get previous chapter
$prev_chapter = $current_chapter - 1;
$prev_chapter_url = $prev_chapter > 0 ? home_url("/index.php/chuong/chuong-{$prev_chapter}-{$story_slug}/") : '';

get_header();
?>

<main id="primary" class="site-main">
    <div class="container py-4">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-4">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <?php if ($truyen) : ?>
                        <div class="truyen-link mb-3">
                            <a href="<?php echo get_permalink($truyen_id); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-book"></i> Về truyện: <?php echo esc_html($truyen->post_title); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php
                // Kiểm tra chương tiếp theo có bị khóa không
                $next_chapter_locked = false;
                $next_chapter_price = 0;
                if ($next_chapter) {
                    $next_chapter_number = $current_chapter_number + 1;
                    $next_chapter_locked = $locked_from && $next_chapter_number >= $locked_from;
                    if ($next_chapter_locked) {
                        // Lấy giá chương (ưu tiên giá riêng của chương)
                        $next_chapter_price = get_post_meta($next_chapter->ID, '_chapter_price', true);
                        if ($next_chapter_price === '') {
                            $next_chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                        }
                        if ($next_chapter_price === '') {
                            $next_chapter_price = 0;
                        }
                    }
                }
                ?>
                <nav class="chapter-navigation mt-4">
                    <div class="row">
                        <div class="col-6">
                            <?php if ($prev_chapter > 0) : ?>
                                <a href="<?php echo home_url("/index.php/chuong/chuong-{$prev_chapter}-{$story_slug}/"); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-chevron-left"></i> Chương trước
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 text-end">
                            <?php 
                            // Check if next chapter exists and is locked
                            $next_chapter = $current_chapter + 1;
                            $next_chapter_url = home_url("/index.php/chuong/chuong-{$next_chapter}-{$story_slug}/");
                            
                            // Get next chapter post to check if it exists
                            $all_chapters  = get_posts(array(
                                'post_type' => 'chuong_truyen',
                                'meta_query' => array(
                                    array(
                                        'key' => 'chuong_with_truyen',
                                        'value' => $truyen_id,
                                        'compare' => '='
                                    )
                                ),
                                'posts_per_page' => -1,
                                'orderby' => 'date',
                                'order' => 'ASC',
                            ));


                            $next_chapter_post = null;

                            for ($i = 0; $i < count($all_chapters); $i++) {
                                if ($all_chapters[$i]->ID == get_the_ID() && isset($all_chapters[$i + 1])) {
                                    $next_chapter_post = $all_chapters[$i + 1];
                                    break;
                                }
                            }


                            if (!empty($next_chapter_post)) :
                                $next_chapter_locked = $locked_from && $next_chapter >= $locked_from;
                                // Get chapter price
                                $next_chapter_price = get_post_meta($next_chapter_post->ID, '_chapter_price', true);
                                if ($next_chapter_price === '') {
                                    $next_chapter_price = get_post_meta($truyen_id, '_chapter_price', true);
                                }
                                if ($next_chapter_price === '') {
                                    $next_chapter_price = 0;
                                }
                                
                                if ($next_chapter_locked && !can_user_read_chapter($user_id, $next_chapter_post->ID, $truyen_id)) : ?>
                                    <a href="javascript:void(0)" class="btn btn-outline-primary buy-next-chapter" 
                                       data-chapter-id="<?php echo $next_chapter_post->ID; ?>"
                                       data-truyen-id="<?php echo $truyen_id; ?>"
                                       data-price="<?php echo number_format($next_chapter_price); ?>">
                                        Chương sau <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $next_chapter_url; ?>" class="btn btn-outline-primary">
                                        Chương sau <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>

                <script>
                jQuery(document).ready(function($) {
                    $('.buy-next-chapter').on('click', function(e) {
                        e.preventDefault();
                        var chapterId = $(this).data('chapter-id');
                        var truyenId = $(this).data('truyen-id');
                        var price = $(this).data('price');
                        
                        Swal.fire({
                            title: 'Xác nhận mua chương?',
                            html: `Bạn có muốn mua chương này với giá <strong>${price} Kim tệ</strong> không?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Mua ngay',
                            cancelButtonText: 'Hủy',
                            showLoaderOnConfirm: true,
                            preConfirm: () => {
                                return $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'buy_chapter',
                                        chapter_id: chapterId,
                                        truyen_id: truyenId,
                                        nonce: '<?php echo wp_create_nonce('buy_chapter_nonce'); ?>'
                                    }
                                });
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                if (result.value.success) {
                                    Swal.fire({
                                        title: 'Thành công!',
                                        text: result.value.data.message,
                                        icon: 'success'
                                    }).then(() => {
                                        // Chuyển hướng đến chương đã mua
                                        window.location.href = '<?php echo get_permalink($next_chapter_post->ID); ?>';
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: result.value.data,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    });
                });
                </script>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
// Thêm phần comment
if (comments_open() || get_comments_number()) :
    echo '<div class="container py-4">';
    echo '<div class="card">';
    echo '<div class="card-body">';
    echo '<h3 class="card-title mb-4">Bình luận</h3>';
    // Đảm bảo post ID được truyền vào
    global $post;
    $post_id = $post->ID;
    comments_template('', true);
    echo '</div>';
    echo '</div>';
    echo '</div>';
endif;
?>

<?php get_footer(); ?> 