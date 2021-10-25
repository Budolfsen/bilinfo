<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://podi.dk
 * @since      1.0.0
 *
 * @package    bilinfo
 * @subpackage bilinfo/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
  <p><strong><?php esc_attr_e('Warning', $this->bilinfo); ?>: </strong><?php esc_attr_e('Do not change these values unless you know what you\'re doing.', $this->bilinfo); ?></p>
  <form method="post" name="bilinfo_options" action="options.php">

    <?php
    //Grab all options
    $options = get_option($this->bilinfo);

    // Cleanup
    $base_url = $options['base-url'] ?: "https://gw.bilinfo.net/listingapi/api/export";
    $username = $options['username'] ?: '';
    $password = $options['password'] ?: '';
    ?>

    <?php
    settings_fields($this->bilinfo);
    do_settings_sections($this->bilinfo);
    ?>
    <h3>Bilinfo</h3>
    <table class="form-table">
      <tbody>
        <tr>
          <!-- Base url -->
          <th><label for="<?php echo $this->bilinfo; ?>-base-url"><?php esc_attr_e('Base url', $this->bilinfo); ?></label></th>
          <td><input type="text" class="regular-text code" id="<?php echo $this->bilinfo; ?>-base-url" name="<?php echo $this->bilinfo; ?>[base-url]" placeholder="Base url" value="<?php if (!empty($base_url)) echo $base_url; ?>" /></td>
        </tr>
        <tr>
          <!-- Username -->
          <th><label for="<?php echo $this->bilinfo; ?>-username"><?php esc_attr_e('Username', $this->bilinfo); ?></label></th>
          <td><input type="text" class="regular-text username" id="<?php echo $this->bilinfo; ?>-username" name="<?php echo $this->bilinfo; ?>[username]" placeholder="username" value="<?php if (!empty($username)) echo $username; ?>" /></td>
        </tr>
        <tr>
          <!-- Password-->
          <th><label for="<?php echo $this->bilinfo; ?>-password"><?php esc_attr_e('password', $this->bilinfo); ?></label></th>
          <td><input type="text" class="regular-text" id="<?php echo $this->bilinfo; ?>-password" name="<?php echo $this->bilinfo; ?>[password]" placeholder="Password" value="<?php if (!empty($password)) echo $password; ?>" /></td>
        </tr>
      </tbody>
    </table>

    <?php submit_button(__('Save all changes', $this->bilinfo), 'primary', 'submit', TRUE); ?>

  </form>

  <a href="/?bilinfo_update=1&key=sd5d2rf16&force&debug" target="_blank">Opdatér biler</a>

</div>