
<?php get_header();?>

<?php 
if(have_posts()):
    while(have_posts(   )):the_post(   );?>
    <h2><?php the_title();?></h2>
    <p><?php the_content( );?></p>
        <?php 
    endwhile;    

endif;


?>

<?php if (have_post()): while(have_post()):the_post();?>
      <h2><?php the_title(); ?></h2>

      <div class="thunbnail-img">
           <?php the_post_thumbnail('thumbnail'); //150x150?>
           <?php the_post_thumbnail('medium'); //300x300?>
           <?php the_post_thumbnail('large'); //1024x1024?>
           <?php the_post_thumbnail('thumbnail'); // madhesia oregjinale

      </div>
?>
<?php get_footer();


?>