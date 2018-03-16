<?php
/**
 * Locate template.
 * @link https://benmarshall.me/add-wordpress-plugin-template-files/
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/templates/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/plugin/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string  $template_name          Template to load.
 * @param   string  $string $template_path  Path to templates.
 * @param   string  $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function custom_type_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// print_r($template_name);
  // Set variable to search in the templates folder of theme.
  if ( ! $template_path ) :
    $template_path = 'templates/';
  endif;
  // Set default plugin templates path.
  if ( ! $default_path ) :
    $default_path = PLUGIN_DIR_PATH . 'templates/'; // Path to the template folder
    // $default_path = plugin_dir_path( __FILE__ ) . 'templates/'; // Path to the template folder
  endif;
  // Search template file in theme folder.
  $template = locate_template( array(
    $template_path . $template_name,
    $template_name
  ) );
  // Get plugins template file.
  if ( ! $template ) :
    $template = $default_path . $template_name;
  endif;
  return apply_filters( 'custom_type_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see custom_type_locate_template()
 *
 * @param string  $template_name          Template to load.
 * @param array   $args                   Args passed for the template file.
 * @param string  $string $template_path  Path to templates.
 * @param string  $default_path           Default path to template files.
 */
function custom_type_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
  if ( is_array( $args ) && isset( $args ) ) :
    extract( $args );
  endif;
  $template_file = custom_type_locate_template( $template_name, $tempate_path, $default_path );
  if ( ! file_exists( $template_file ) ) :
    _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
    return;
  endif;
  include $template_file;
}

/**
 * Template loader.
 *
 * The template loader will check if WP is loading a template
 * for a specific Post Type and will try to load the template
 * from out 'templates' directory.
 *
 * @since 1.0.0
 *
 * @param string  $template Template file that is being loaded.
 * @return  string          Template file that should be loaded.
 */
function custom_type_template_loader( $template ) {
  $find = array();
  $file = '';
  if( is_singular('custom_type') ):
    $file = 'single-custom_type.php';
  elseif( is_tax('custom_type') ):
    $file = 'archive-custom_type.php';
  endif;
  if ( !empty($file) && file_exists( custom_type_locate_template( $file ) ) ) :
    $template = custom_type_locate_template( $file );
  endif;
  return $template;
}
add_filter( 'template_include', 'custom_type_template_loader' );
