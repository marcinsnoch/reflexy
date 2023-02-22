<?php
/*
 * Template Name: Classic Full Width For Reflexy
 * Template Post Type: reflexy
 */

get_header();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-8 offset-md-2">
            <h2 class="text-center mb-5"><?php post_type_archive_title(); ?></h2>
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('mb-5 '); ?>>
                        <h4 class="text-center"><?php the_title(); ?></h4>
                        <div class="d-flex flex-column">
                            <?php if (has_post_thumbnail()) : ?>
                                
                                <?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
                            <?php endif; ?>
                            <div class="p-0">
                                <?php echo the_content(); ?>
                            </div>
                            <div class="meta pt-1">
                                <?php echo get_avatar(get_the_author_meta('user_email'), '24', ''); ?>
                                <span class="author"><?php the_author_posts_link(); ?></span>
                                <span class="date"><?php the_time('j/m/y'); ?></span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
                <?php the_posts_pagination(array('mid_size' => 2)); ?>  
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
