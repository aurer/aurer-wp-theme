<?php
/*
    Register custom post type
*/
add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'project',
        array(
            'label' => __( 'Projects' ),
            'singular_label' => __( 'Project' ),
            'show_ui' => true,
            'public' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'rewrite' => array('slug' => 'project'),
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', 'comments'),
            'taxonomies' => array('category', 'post_tag', 'menu_order')
        )
    );
}

/*
    Add custom field
*/
add_action('admin_init', 'add_project_meta');
add_action('save_post', 'update_description');
function add_project_meta(){
    add_meta_box("description", "Description", "project_description", "project", "normal", "low");
}
function project_description(){
    global $post;
    $instance = get_post_custom($post->ID);
    ?><div id="description">
            <input id="link_description" type="text" size="30" name="description" value="<?php echo $instance["description"][0] ?>" />
            <p>This will show under the thumbnail on the Projects page.</p>
    </div><?php
}
function update_description(){
    global $post;
    update_post_meta($post->ID, "description", $_POST["description"]);
}

/*
    Add projects to blog content
*/
add_filter( 'pre_get_posts', 'my_get_posts' );
function my_get_posts( $query ) {
    if ( is_home() && false == $query->query_vars['suppress_filters'] )
        $query->set( 'post_type', array( 'post', 'project' ) );
    return $query;
}