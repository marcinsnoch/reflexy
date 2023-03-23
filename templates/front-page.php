<div class="slice type2">
    <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
        <a href="./reflexy/"><h2 class="slice-title"><?php echo esc_html('REFLEXY'); ?></h2></a>
        <div class="card-deck">
            <?php if ($custom_query->have_posts()) : ?>
                <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                    <div class="card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array('class' => 'card-img-top mb-1')); ?>
                            <?php the_content(); ?>
                        <?php else: ?>
                            <?php the_content(); ?>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
    </article>
</div>
