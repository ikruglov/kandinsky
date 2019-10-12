<?php
/**
 * The main template file.
 */
 
$posts = $wp_query->posts; 
$paged = (get_query_var('paged', 0)) ? get_query_var('paged', 0) : 1;

get_header();
?>

<div class="container heading">
    <?php knd_section_title(); ?>
</div>

<?php if(empty($posts)) { ?>
    <div class="main-content listing-bg"><div class="container">
        <div class="empty-message"><?php esc_html_e('Unfortunately, nothing found', 'knd');?></div>
    </div></div>

<?php  } else { ?>
    <?php if(!empty($posts)):?>
    <div class="main-content cards-holder listing-bg archive-post-list <?php if($paged > 1):?>next-page<?php endif?>">
        <div class="container">
            <div class="flex-row start cards-loop">
            <?php
                foreach($posts as $p){
                    knd_post_card($p);
                }
            ?>
            </div>
        </div>
    </div>
    <?php endif;?>


    <div class="paging listing-bg"><?php knd_paging_nav($wp_query); ?></div>
<?php } ?>

<div class="knd-archive-sidebar">

    <?php if(is_home()):?>
        <?php dynamic_sidebar( 'knd-news-archive-sidebar' );?>
    <?php else: ?>
        <?php dynamic_sidebar( 'knd-projects-archive-sidebar' );?>    
    <?php endif ?>
    
</div>

<?php get_footer();