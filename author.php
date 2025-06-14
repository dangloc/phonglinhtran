<?php
/**
 * The template for displaying author profile
 */

get_header();

$author = get_user_by('slug', get_query_var('author_name'));
$author_id = $author->ID;

// Get author stats
$published_posts = count_user_posts($author_id, 'truyen_chu', true);
$user_balance = get_user_meta($author_id, '_user_balance', true);
$vip_name = get_user_meta($author_id, '_user_vip_name', true);
$is_vip = check_user_vip_status($author_id);

$tiennu = get_template_directory_uri() . '/assets/images/tiennu.png';
$ngocnu = get_template_directory_uri() . '/assets/images/ngocnu.png';
$tienco = get_template_directory_uri() . '/assets/images/tienco.png';
$huyennu = get_template_directory_uri() . '/assets/images/huyennu.png';
$thannu = get_template_directory_uri() . '/assets/images/thannu.png';
$thienton = get_template_directory_uri() . '/assets/images/thienton.png';

$type_vip_name = ['Tiên nữ', 'Ngọc nữ', 'Tiên cô', 'Huyền nữ', 'Thần nữ', 'Thiên tôn'];

// Get author's stories
$args = array(
    'post_type' => 'truyen_chu',
    'author' => $author_id,
    'posts_per_page' => 12,
    'orderby' => 'date',
    'order' => 'DESC'
);
$author_stories = new WP_Query($args);
?>

<div class="container py-5 author-page">
    <div class="row">
        <!-- Author Profile Card -->
        <div class="col-md-12 mb-4">
            <div class="card-author" style="background-image: url('<?php echo get_avatar_url($author_id, ['size' => '300']); ?>');">
                <div class="card-author-overlay"></div>
                <div class="card-body text-center">
                    <div class="avatar-author">
                        <div class="avatar-author-bg
                            <?php
                                switch ($vip_name) {
                                    case 'Tiên nữ':
                                        echo 'tien-nu';
                                        break;
                                    case 'Ngọc nữ':
                                        echo 'ngoc-nu';
                                        break;
                                    case 'Tiên cô':
                                        echo 'tien-co';
                                        break;
                                    case 'Huyền nữ':
                                        echo 'huyen-nu';
                                        break;
                                    case 'Thần nữ':
                                        echo 'than-nu';
                                        break;
                                    case 'Thiên tôn':
                                        echo 'thien-ton';
                                        break;
                                    default:
                                        // Nếu không khớp với loại VIP nào
                                        echo '';
                                        break;
                                }
                            ?>
                        ">
                            <?php 
                                switch ($vip_name) {
                                    case 'Tiên nữ':
                                        echo '<img src="' . $tiennu . '" alt="Tiên nữ">';
                                        break;
                                    case 'Ngọc nữ':
                                        echo '<img src="' . $ngocnu . '" alt="Ngọc nữ">';
                                        break;
                                    case 'Tiên cô':
                                        echo '<img src="' . $tienco . '" alt="Tiên cô">';
                                        break;
                                    case 'Huyền nữ':
                                        echo '<img src="' . $huyennu . '" alt="Huyền nữ">';
                                        break;
                                    case 'Thần nữ':
                                        echo '<img src="' . $thannu . '" alt="Thần nữ">';
                                        break;
                                    case 'Thiên tôn':
                                        echo '<img src="' . $thienton . '" alt="Thiên tôn">';
                                        break;
                                    default:
                                        // Nếu không khớp với loại VIP nào
                                        echo '';
                                        break;
                                }
                            ?>
                        </div>
                        <?php echo get_avatar($author_id, 120, '', '', array('class' => 'rounded-circle mb-3')); ?>
                    </div>
                    <?php if (is_user_logged_in() && get_current_user_id() === $author_id): ?>
                        <div class="avatar-actions mb-3">
                            <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#updateAvatarModal">
                                <i class="fas fa-camera"></i> Cập nhật ảnh
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" id="deleteAvatarBtn">
                                <i class="fas fa-trash"></i> Xóa ảnh
                            </button>
                        </div>
                    <?php endif; ?>
                    <h3 class="card-title mb-2"><?php echo esc_html($author->display_name); ?></h3>
                    <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                        <?php if ($vip_name): ?>
                            <button class="btn-cus-vip <?php
                                 switch ($vip_name) {
                                    case 'Tiên nữ':
                                        echo 'btn-cus-vip--tien-nu';
                                        break;
                                    case 'Ngọc nữ':
                                        echo 'btn-cus-vip--ngoc-nu';
                                        break;
                                    case 'Tiên cô':
                                        echo 'btn-cus-vip--tien-co';
                                        break;
                                    case 'Huyền nữ':
                                        echo 'btn-cus-vip--huyen-nu';
                                        break;
                                    case 'Thần nữ':
                                        echo 'btn-cus-vip--than-nu';
                                        break;
                                    case 'Thiên tôn':
                                        echo 'btn-cus-vip--thien-ton';
                                        break;
                                    default:
                                        echo '';
                                        break;
                                }
                                ?>">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <?php echo esc_html($vip_name); ?>
                            </button>
                        <?php endif; ?>
                        <?php if ($is_vip): ?>
                            <div class="vip-badge" title="Tài khoản VIP">
                                <i class="fas fa-crown text-warning"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <div class="text-center">
                            <h5 class="mb-0"><?php echo number_format($published_posts); ?></h5>
                            <small class="text-muted">Truyện</small>
                        </div>
                        <div class="text-center">
                            <h5 class="mb-0"><?php echo number_format((float)$user_balance); ?></h5>
                            <small class="text-muted">Kim tệ</small>
                        </div>
                    </div>
                    <?php if ($author->description): ?>
                        <p class="card-text"><?php echo nl2br(esc_html($author->description)); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Author's Stories -->
        <div class="col-md-12">
            <div class="">
                <div class="card-body">
                    <?php if ($author_stories->have_posts()): ?>
                        <h4 class="card-title mb-4">Truyện của <?php echo esc_html($author->display_name); ?></h4>
                        <div class="row">
                            <?php while ($author_stories->have_posts()): $author_stories->the_post(); 
                                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                                if (!$thumbnail) {
                                    $thumbnail = get_template_directory_uri() . '/assets/images/no-image.jpg';
                                }
                            ?>
                                 <?php get_template_part( 'template-parts/home/item-card' ); ?>
                            <?php endwhile; ?>
                        </div>

                        <?php if ($author_stories->max_num_pages > 1): ?>
                            <div class="mt-4">
                                <?php
                                echo paginate_links(array(
                                    'total' => $author_stories->max_num_pages,
                                    'current' => max(1, get_query_var('paged')),
                                    'prev_text' => '&laquo; Trước',
                                    'next_text' => 'Sau &raquo;',
                                    'type' => 'list',
                                    'class' => 'pagination justify-content-center'
                                ));
                                ?>
                            </div>
                        <?php endif; ?>

                    <?php else: 
                        // Hiển thị danh sách favorites cho người dùng thường
                        if (function_exists('get_user_favorites')): ?>
                            <h4 class="card-title mb-4">Truyện yêu thích của bạn</h4>
                            <?php 
                            // Sử dụng shortcode của plugin Favorites
                            echo do_shortcode('[user_favorites include_thumbnails="true" thumbnail_size="medium" post_types="truyen_chu" posts_per_page="12"]');
                            ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (is_user_logged_in() && get_current_user_id() === $author_id): ?>
<!-- Avatar Update Modal -->
<div class="modal fade" id="updateAvatarModal" tabindex="-1" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-black" id="updateAvatarModalLabel">Cập nhật ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="avatarUpdateForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="avatarFile" class="form-label">Chọn ảnh mới</label>
                        <input type="file" class="form-control" id="avatarFile" name="avatar" accept="image/*" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Handle avatar update
    $('#avatarUpdateForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('action', 'update_user_avatar');
        formData.append('security', '<?php echo wp_create_nonce("update_avatar_nonce"); ?>');

        // Show loading state
        Swal.fire({
            title: 'Đang cập nhật...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Ảnh đại diện đã được cập nhật',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: response.data || 'Có lỗi xảy ra khi cập nhật ảnh đại diện'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Có lỗi xảy ra khi cập nhật ảnh đại diện'
                });
            }
        });
    });

    // Handle avatar deletion
    $('#deleteAvatarBtn').on('click', function() {
        Swal.fire({
            title: 'Xác nhận xóa?',
            text: "Bạn có chắc chắn muốn xóa ảnh đại diện?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Đang xóa...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'delete_user_avatar',
                        security: '<?php echo wp_create_nonce("delete_avatar_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Ảnh đại diện đã được xóa',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.data || 'Có lỗi xảy ra khi xóa ảnh đại diện'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Có lỗi xảy ra khi xóa ảnh đại diện'
                        });
                    }
                });
            }
        });
    });
});
</script>
<?php endif; ?>

<?php get_footer(); ?> 