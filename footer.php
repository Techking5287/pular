<?php
/**
 * The template for displaying the footer.
 *
 * @package Pulsar
 */

?>

		<footer id="colophon" class="site-footer container">
			<div class="site-footer__inner">
				<?php
				printf(
					esc_html__( 'Copyright &#169; %1$s %2$s', 'pulsar' ),
					date_i18n( 'Y' ),
					get_bloginfo( 'name' )
				);
				?>
			</div>
		</footer>
	</div>
	<?php wp_footer(); ?>
	</body>
</html>
