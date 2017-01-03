<?php
/**
 * Admin settings page.
 */

class RHMSettingsPage {
  /**
  * Holds the values to be used in the fields callbacks
  */
  private $options;

  /**
  * Start up
  */
  public function __construct() {
    add_action('admin_menu', array($this, 'rhm_add_setting_page' ));
    add_action('admin_init', array($this, 'rhm_page_init'));
  }

  /**
  * Add options page
  */
  public function rhm_add_setting_page() {
    // This page will be under "Settings"
    add_options_page(
      __('RHM Theme Setting', 'rhm'),
      __('Theme Setting', 'rhm'),
      'manage_options',
      'rhm-setting-admin',
      array($this, 'rhm_reate_admin_page')
    );
  }

  /**
  * Options page callback
  */
  public function rhm_reate_admin_page() {
    // Set class property
    $this->options = get_option('rhm_board_settings');

    ?>
    <div class="wrap">
      <h1><?php echo __('Theme settings', 'rhm') ?></h1>
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields('rhm_option_config');
        do_settings_sections('rhm-setting-admin');
        submit_button();
      ?>
      </form>
    </div>
    <?php
  }

  /**
  * Register and add settings
  */
  public function rhm_page_init() {
    register_setting('rhm_option_config', 'rhm_board_settings');

    // Setting ID
    add_settings_section(
      'rhm_section_id', // ID
      __('Social Enable', 'rhm'), // Title
      array( $this, 'rhm_print_section_info' ), // Callback
      'rhm-setting-admin' // Page
    );

    add_settings_field(
      'rhm_facebook_social',
      __('Facebook', 'rhm'),
      array( $this, 'rhm_form_checkbox' ), // Callback
      'rhm-setting-admin', // Page
      'rhm_section_id',
      'rhm_facebook_social'
    );

    add_settings_field(
      'rhm_googleplus_social',
      __('Google Plus', 'rhm'),
      array( $this, 'rhm_form_checkbox' ), // Callback
      'rhm-setting-admin', // Page
      'rhm_section_id',
      'rhm_googleplus_social'
    );

    add_settings_field(
      'rhm_twitter_social',
      __('Twitter', 'rhm'),
      array( $this, 'rhm_form_checkbox' ), // Callback
      'rhm-setting-admin', // Page
      'rhm_section_id',
      'rhm_twitter_social'
    );
  }

  /**
  * Print the Section text
  */
  public function rhm_print_section_info() {
    echo __("Use shortcode [rhm_share_this]", 'rhm');
  }

  /**
  * Get the settings option array and print one of its values
  */
  public function rhm_form_checkbox($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    $checked = "";
    if($value){
      $checked = " checked='checked' ";
    }
    printf('<input type="checkbox" id="form-id-%s" name="rhm_board_settings[%s]" value="1" %s/>', $name, $name, $checked);
  }

  public function rhm_form_textfield($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<input type="text" size=60 id="form-id-%s" name="rhm_board_settings[%s]" value="%s" />', $name, $name, $value);
  }

  public function rhm_form_textarea($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<textarea cols="100%%" rows="3" type="textarea" id="form-id-%s" name="rhm_board_settings[%s]">%s</textarea>', $name, $name, $value);
  }
}
