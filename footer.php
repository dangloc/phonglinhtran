<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package commicpro
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="site-info container">
			<div class="row">
				<div class="col-md-4">
					<h4>Thông tin liên hệ</h4>
					<ul>
						<li>Email: <a href="mailto:minerrvary1111@gmail.com">minerrvary1111@gmail.com</a></li>
					</ul>

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php bloginfo('template_url'); ?>/assets/images/logo.svg" alt="">
					</a>
				</div>
				<div class="col-md-4">
					<h4>Thông báo và hướng dẫn</h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer-menu',
							'menu_id'        => 'footer-menu',
						)
					);
					?>
				</div>
				<div class="col-md-4 overfollow-hidden">
					<h4>Theo dõi fanpage</h4>
					<iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FCaMapNovel&tabs=timeline&width=280&height=180&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=681892607471462" width="280" height="180" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
				</div>
			</div>
			<div class="d-flex justify-content-center">
				<p class="copyright">Copyright © 2025 TuSacHiep.xyz. All rights reserved.</p>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
