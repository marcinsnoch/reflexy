<div class="slice type5">
    <?php if ($custom_query->have_posts()) : ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
            <a href="./reflexy/"><h2 class="slice-title"><?php echo esc_html('REFLEXY'); ?></h2></a>
            <div class="card-deck">
                <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                    <div class="card">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large', array('class' => 'card-img')); ?>
                        <?php endif; ?>
                        <?php echo the_content(); ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </article>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>
