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

<!-- Advanced Movie Grid -->
<div class="container py-5">
  <h2 class="mb-4 text-center fw-bold">Movie Collection</h2>
  <div class="d-flex justify-content-center mb-4">
    <!-- Genre Filter -->
    <?php
    $genres = get_terms('genre');
    if ($genres && !is_wp_error($genres)) {
      echo '<div class="btn-group" role="group">';
      echo '<a href="?" class="btn btn-outline-primary">All</a>';
      foreach ($genres as $genre) {
        echo '<a href="?genre=' . esc_attr($genre->slug) . '" class="btn btn-outline-primary">' . esc_html($genre->name) . '</a>';
      }
      echo '</div>';
    }
    ?>
  </div>
  <div class="row g-4">
    <?php
    $genre_filter = isset($_GET['genre']) ? sanitize_text_field($_GET['genre']) : '';
    $args = array(
      'post_type' => 'movie',
      'posts_per_page' => 12,
      'tax_query' => $genre_filter ? array([
        'taxonomy' => 'genre',
        'field' => 'slug',
        'terms' => $genre_filter
      ]) : '',
    );
    $movies = new WP_Query($args);
    if ($movies->have_posts()) {
      while ($movies->have_posts()) {
        $movies->the_post();
        $genres = get_the_terms(get_the_ID(), 'genre');
        ?>
        <div class="col-md-4 col-lg-3">
          <div class="card movie-card shadow-sm h-100">
            <?php if (has_post_thumbnail()) { ?>
              <div class="card-img-top movie-img-overlay">
                <?php the_post_thumbnail('medium', ['class' => 'img-fluid rounded-top']); ?>
                <div class="overlay-gradient"></div>
              </div>
            <?php } ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold text-truncate"><?php the_title(); ?></h5>
              <p class="card-text text-muted small flex-grow-1"><?php echo get_the_excerpt(); ?></p>
              <?php if ($genres && !is_wp_error($genres)) {
                echo '<div class="mb-2">';
                foreach ($genres as $genre) {
                  echo '<span class="badge bg-primary me-1">' . esc_html($genre->name) . '</span>';
                }
                echo '</div>';
              } ?>
              <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm mt-auto"><i class="bi bi-eye"></i> View</a>
            </div>
          </div>
        </div>
        <?php
      }
      wp_reset_postdata();
    } else {
      echo '<div class="col-12"><div class="alert alert-warning text-center">No movies found.</div></div>';
    }
    ?>
  </div>
</div>
<!-- Advanced Add/Remove Forms -->
<div class="container py-4">
  <div class="row g-4">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-success text-white fw-bold"><i class="bi bi-plus-circle"></i> Add Movie</div>
        <div class="card-body">
          <!-- Add Movie Form (hidden by default) -->
          <div class="add-movie-section my-5" id="add-movie-section" style="display:none;">
            <h2>Add a Movie</h2>
            <form method="post" class="mb-4" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="ds_movie_title" class="form-label">Movie Title</label>
                <input type="text" name="ds_movie_title" id="ds_movie_title" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="ds_movie_description" class="form-label">Description</label>
                <textarea name="ds_movie_description" id="ds_movie_description" class="form-control" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="ds_movie_image" class="form-label">Movie Image</label>
                <input type="file" name="ds_movie_image" id="ds_movie_image" class="form-control" accept="image/*">
              </div>
              <button type="submit" name="ds_add_movie" class="btn btn-success">Add Movie</button>
            </form>
          </div>
          <script>
          document.getElementById('show-add-movie-form').onclick = function() {
            document.getElementById('add-movie-section').style.display = 'block';
            this.style.display = 'none';
          };
          </script>
          <?php
          if ( isset($_POST['ds_add_movie']) && !empty($_POST['ds_movie_title']) ) {
            $new_movie = array(
              'post_title'   => sanitize_text_field($_POST['ds_movie_title']),
              'post_content' => sanitize_textarea_field($_POST['ds_movie_description']),
              'post_type'    => 'movie',
              'post_status'  => 'publish',
            );
            $movie_id = wp_insert_post($new_movie);
            if ($movie_id && !is_wp_error($movie_id)) {
              // Handle image upload
              if ( !empty($_FILES['ds_movie_image']['name']) ) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                $attachment_id = media_handle_upload('ds_movie_image', $movie_id);
                if ( is_numeric($attachment_id) ) {
                  set_post_thumbnail($movie_id, $attachment_id);
                }
              }
              echo '<div class="alert alert-success my-3">Movie added successfully!</div>';
            } else {
              echo '<div class="alert alert-danger my-3">Error adding movie.</div>';
            }
          }
          ?>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-danger text-white fw-bold"><i class="bi bi-trash"></i> Remove Movie</div>
        <div class="card-body">
          <?php
          // Handle movie deletion
          if (isset($_POST['ds_remove_movie']) && !empty($_POST['ds_movie_to_remove'])) {
            $movie_id = intval($_POST['ds_movie_to_remove']);
            if (get_post_type($movie_id) === 'movie') {
              wp_delete_post($movie_id, true);
              echo '<script>window.location.href = window.location.href;</script>';
              exit;
            } else {
              echo '<div class="alert alert-danger my-3">Error: Movie not found or invalid.</div>';
            }
          }
          ?>
          <div class="remove-movie-section my-5">
            <h2>Remove a Movie</h2>
            <form method="post" class="mb-4">
              <div class="mb-3">
                <label for="ds_movie_to_remove" class="form-label">Select Movie</label>
                <select name="ds_movie_to_remove" id="ds_movie_to_remove" class="form-select" required>
                  <option value="">Choose a movie...</option>
                  <?php
                  $movies = get_posts(array('post_type' => 'movie', 'posts_per_page' => -1));
                  foreach ($movies as $movie) {
                    echo '<option value="' . esc_attr($movie->ID) . '">' . esc_html($movie->post_title) . '</option>';
                  }
                  ?>
                </select>
              </div>
              <button type="submit" name="ds_remove_movie" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this movie?');">Remove Movie</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>

