<?php
/**
 * DS Theme complete with Bootstrap via CDN
 */

// Enqueue styles & scripts (Bootstrap 5 CDN + theme CSS)
function ds_enqueue_assets() {
  // Bootstrap CSS
  wp_enqueue_style( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' );

  // Main stylesheet
  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.2', 'all' );

  // Bootstrap JS (bundle includes Popper) in footer
  wp_enqueue_script( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true );

  // Threaded comments only where needed
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets' );

/**
 * Register theme features like menus and supports
 * (Using init to match your lesson flow; after_setup_theme is also fine.)
 */
function ds_setup() {
  add_theme_support( 'menus' );
  register_nav_menu( 'primary', 'Primary Navigation' );
  register_nav_menu( 'footer', 'Footer Navigation' );

  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video' ) );
}
add_action( 'init', 'ds_setup' );
// Register widget areas (sidebars)
function ds_widgets_init() {
  register_sidebar( array(
    'name'          => __( 'Primary Sidebar', 'dstheme' ),
    'id'            => 'primary',
    'description'   => __( 'Main sidebar that appears on the right.', 'dstheme' ),
    'class'         => 'sidebar-primary',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget'  => '</aside>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );

  register_sidebar( array(
    'name'          => __( 'Secondary Sidebar', 'dstheme' ),
    'id'            => 'secondary',
    'description'   => __( 'Optional secondary sidebar (e.g., footer or left side).', 'dstheme' ),
    'class'         => 'sidebar-secondary',
    'before_widget' => '<ul><li id="%1$s" class="widget %2$s">',
    'after_widget'  => '</li></ul>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ) );
}
add_action( 'widgets_init', 'ds_widgets_init' );

// Example custom widget
class DS_Simple_Text_Widget extends WP_Widget {

  public function __construct() {
    parent::__construct(
      'ds_simple_text', // Base ID
      __( 'DS Simple Text', 'dstheme' ), // Name
      array( 'description' => __( 'A simple text widget for DS Theme.', 'dstheme' ) )
    );
  }

  public function widget( $args, $instance ) {
    echo $args['before_widget'];
    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $text  = ! empty( $instance['text'] ) ? $instance['text'] : '';
    if ( ! empty( $title ) ) {
      $title = apply_filters( 'widget_title', $title );
      echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
    }
    if ( ! empty( $text ) ) {
      echo '<div class="textwidget">' . wp_kses_post( $text ) . '</div>';
    }
    echo $args['after_widget'];
  }

  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
    $text  = ! empty( $instance['text'] ) ? $instance['text'] : '';
    ?>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'dstheme' ); ?></label>
      <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
             name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
             value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php _e( 'Text:', 'dstheme' ); ?></label>
      <textarea class="widefat" rows="5" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo esc_textarea( $text ); ?></textarea>
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = (! empty( $new_instance['title'] )) ? sanitize_text_field( $new_instance['title'] ) : '';
    $instance['text']  = (! empty( $new_instance['text'] )) ? wp_kses_post( $new_instance['text'] ) : '';
    return $instance;
  }
}

add_action( 'widgets_init', function() {
  register_widget( 'DS_Simple_Text_Widget' );
} );
function mytheme_pagination($query = null, $args = array()) {
  if ($query instanceof WP_Query) {
    $q = $query;
  } else {
    global $wp_query;
    $q = $wp_query;
  }

  if (empty($q->max_num_pages) || $q->max_num_pages < 2) {
    // Debug output for troubleshooting
    echo '<!-- Pagination not shown: max_num_pages = ' . esc_html($q->max_num_pages) . ' -->';
    return;
  }

  $big = 999999999; // need an unlikely integer
  $pagination_args = wp_parse_args($args, array(
    'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format'    => '?paged=%#%',
    'current'   => max(1, get_query_var('paged')),
    'total'     => $q->max_num_pages,
    'prev_text' => __('« Prev', 'dstheme'),
    'next_text' => __('Next »', 'dstheme'),
    'type'      => 'plain', // Change to 'plain' for simple numbered links
  ));
  echo '<nav class="pagination-nav">';
  echo paginate_links($pagination_args);
  echo '</nav>';
}




function register_taxonomies_movies_genres() {
  $labels = array(
    'name'              => _x( 'Genres', 'taxonomy general name', 'dstheme' ),
    'singular_name'     => _x( 'Genre', 'taxonomy singular name', 'dstheme' ),
    'search_items'      => __( 'Search Genres', 'dstheme' ),
    'all_items'        => __( 'All Genres', 'dstheme' ),
    'parent_item'       => __( 'Parent Genre', 'dstheme' ),
    'parent_item_colon' => __( 'Parent Genre:', 'dstheme' ),
    'edit_item'         => __( 'Edit Genre', 'dstheme' ),
    'update_item'       => __( 'Update Genre', 'dstheme' ),
    'add_new_item'      => __( 'Add New Genre', 'dstheme' ),
    'new_item_name'     => __( 'New Genre Name', 'dstheme' ),
    'menu_name'         => __( 'Genre', 'dstheme' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'genre' ),
  );

  register_taxonomy( 'genre', array( 'movie' ), $args );
}

function register_movie_post_type() {
  $labels = array(
    'name'               => _x( 'Movies', 'post type general name', 'dstheme' ),
    'singular_name'      => _x( 'Movie', 'post type singular name', 'dstheme' ),
    'menu_name'          => _x( 'Movies', 'admin menu', 'dstheme' ),
    'name_admin_bar'     => _x( 'Movie', 'add new on admin bar', 'dstheme' ),
    'add_new'            => _x( 'Add New', 'movie', 'dstheme' ),
    'add_new_item'       => __( 'Add New Movie', 'dstheme' ),
    'new_item'           => __( 'New Movie', 'dstheme' ),
    'edit_item'          => __( 'Edit Movie', 'dstheme' ),
    'view_item'          => __( 'View Movie', 'dstheme' ),
    'all_items'          => __( 'All Movies', 'dstheme' ),
    'search_items'       => __( 'Search Movies', 'dstheme' ),
    'parent_item_colon'  => __( 'Parent Movies:', 'dstheme' ),
    'not_found'          => __( 'No movies found.', 'dstheme' ),
    'not_found_in_trash' => __( 'No movies found in Trash.', 'dstheme' )
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'movie' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
  );

  register_post_type( 'movie', $args );
}
add_action( 'init', 'register_movie_post_type' );
add_action( 'init', 'register_taxonomies_movies_genres' );

