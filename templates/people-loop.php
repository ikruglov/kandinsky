<div class="knd-section-extend-on-large">

	<?php if ( $posts->have_posts() ) : ?>
	
	    <div class="knd-people-gallery flex-row centered">

		<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
			<div id="su-post-<?php the_ID(); ?>" class="person flex-cell flex-sm-6 flex-md-col-5">
			    <a href="<?php the_permalink(); ?>">
			    <article class="tpl-person card">
			        <div class="entry-data">
				        <?php if ( has_post_thumbnail( get_the_ID() ) ) : ?>
					        <?php the_post_thumbnail('square'); ?>
				        <?php endif; ?>
				        <h4 class="entry-title"><?php the_title(); ?></h4>
				        <div class="entry-meta"><?php the_excerpt(); ?></div>
				    </div>
                </article>
                </a>
                <a href="<?php the_permalink(); ?>">Читать подробнее</a>
			</div>
		<?php endwhile; ?>
		
		</div>

	<?php else : ?>
		<h4><?php _e( 'Posts not found', 'shortcodes-ultimate' ); ?></h4>
	<?php endif; ?>

</div>
