<?php
/*
 * Template Name: Single Reflexy
 * Template Post Type: reflexy
 */

get_header();
?>

<div id="reflexy-single" class="container">
	<div class="row">
		<div class="content col-lg-8 mx-auto align-items-center" style="padding-right: 0; padding-left: 0">
			<?php while (have_posts()) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('mb-5'); ?>>
				<h1 class="text-center"><?php the_title(); ?></h1>
				<div class="d-flex flex-column">
					<?php if (has_post_thumbnail()) : ?>
					<?php the_post_thumbnail('large', ['class' => 'img-fluid mb-0 mt-0']); ?>
					<?php endif; ?>
					<div class="entry-content mb-0 mt-0">
						<?php the_content(); ?>
					</div>
					<div class="meta pt-1">
						<?php echo get_avatar(get_the_author_meta('user_email'), '24', '', 'Avatar', ['class' => 'rounded-circle']); ?>
						<span class="author"><?php the_author_posts_link(); ?></span>
						<span class="date"><?php the_time('j F Y'); ?></span>
						<?php if (get_the_terms(get_the_ID(), 'reflexy_category')) : ?>
						<span class="categories"><?php the_terms(get_the_ID(), 'reflexy_category', 'Kategorie: ', ', '); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</article>

			<!-- Post Navigation -->
			<nav class="post-navigation mb-5">
				<div class="nav-links">
					<div class="nav-previous"><?php previous_post_link('%link', '&laquo; Poprzedni Reflex'); ?></div>
					<div class="nav-next"><?php next_post_link('%link', 'Następny Reflex &raquo;'); ?></div>
                </div>
			</nav>
		</div>

		<!-- Comments -->
		<?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        ?>
		<?php endwhile; ?>
	</div>
</div>

<?php get_footer(); ?>