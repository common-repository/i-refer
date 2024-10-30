<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   i-Refer
 * @author    i-Refer <wcd@i-refer.global>
 * @license   LGPLv2.1
 * @link      https://i-refer.app
 */
?>

<div class="wrap">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <div style="display: flex; width: 100%;">
    <div style="display: flex; width: 100%;">
      <div id="lottie-animation" style="width: 50%; height: 400px; background-color:black;"></div>
      <div class="right-column-settings-page" style="width: 50%; padding: 20px;">
        <h2>I-Refer WooCommerce Plugin <?php echo IREFER_VERSION; ?></h2>
      </div>
    </div>
  </div>
  <div id="tabs" class="settings-tab">
    <div class="right-column-settings-page metabox-holder"></div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var animation = lottie.loadAnimation({
        container: document.getElementById('lottie-animation'), // Required
        path: '<?php echo esc_url( plugins_url( '/assets/intro.json', IREFER_PLUGIN_ABSOLUTE ) ); ?>', // Required
        renderer: 'svg', // Required
        loop: true, // Optional
        autoplay: true, // Optional
      });
    });
  </script>
</div>
