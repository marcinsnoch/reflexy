<a href="./reflexy/"><h2 class="slice-title"><?php echo esc_html('REFLEXY'); ?></h2></a>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-8 offset-md-2">
            <div class="d-flex flex-column">
                <?php if ($custom_query->have_posts()) : ?>
                    <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
                            <h4 class="text-center"><?php the_title(); ?></h4>
                            <div class="d-flex flex-column">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
                                <?php endif; ?>
                                <div class="p-0">
                                    <?php echo the_content(); ?>
                                </div>
                                <div class="meta pt-1">
                                    <?php echo get_avatar(get_the_author_meta('user_email'), '24', '', 'Avatar', ['class' => 'rounded-circle']); ?>
                                    <span class="author"><?php the_author_posts_link(); ?></span>
                                    <span class="date"><?php the_time('j/m/y'); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>