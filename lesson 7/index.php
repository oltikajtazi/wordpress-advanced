<?php get_header(); ?>



<?php
// Custom pagination at the top
if ( have_posts() ) {
  global $wp_query;
  $big = 999999999;
  $pagination = paginate_links(array(
    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format'    => '?paged=%#%',
    'current'   => max(1, get_query_var('paged')),
    'total'     => $wp_query->max_num_pages,
    'prev_text' => __('« Prev', 'dstheme'),
    'next_text' => __('Next »', 'dstheme'),
    'type'      => 'plain',
  ));
  if ($pagination) {
    echo '<nav class="pagination-nav my-4">' . $pagination . '</nav>';
  }
}
?>

<?php if ( have_posts() ) : ?>
  <div class="row">
    <div class="col-md-8">
      <div class="row g-4">
      <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class('col-12'); ?> id="post-<?php the_ID(); ?>">
          <div class="card h-100">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="thumbnail-img"><?php the_post_thumbnail('medium_large', array('class'=>'card-img-top')); ?></div>
            <?php endif; ?>
            <div class="card-body">
              <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <small class="text-muted d-block mb-2">
                Posted on: <?php the_time('F j, Y'); ?> at <?php the_time('g:i a'); ?>, in <?php the_category(', '); ?>
              </small>
              <div class="card-text"><?php the_excerpt(); ?></div>
            </div>
          </div>
        </article>
      <?php endwhile; ?>
      </div>
    </div>
    <aside class="col-md-4">
      <?php get_sidebar( 'primary' ); ?>
    </aside>
  </div>
<?php endif; ?>

<div class="my-4">
  <?php
  // Custom pagination at the bottom
  if ( have_posts() ) {
    global $wp_query;
    $big = 999999999;
    $pagination = paginate_links(array(
      'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'format'    => '?paged=%#%',
      'current'   => max(1, get_query_var('paged')),
      'total'     => $wp_query->max_num_pages,
      'prev_text' => __('« Prev', 'dstheme'),
      'next_text' => __('Next »', 'dstheme'),
      'type'      => 'plain',
    ));
    if ($pagination) {
      echo '<nav class="pagination-nav my-4">' . $pagination . '</nav>';
    }
  }
  ?>
</div>

<div class="rounded"> this div is rounded</div>
<div class="image"> this div uses image</div>
<div class="olti"> this div uses image </div>

<div class="shadow">this div has shadows</div>
<div class="inershadow"> this div has shadows</div>


<?php get_footer(); ?>

