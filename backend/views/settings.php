<?php
/*
 * Retrieve these settings on front end in either of these ways:
 *   $my_setting = cmb2_get_option( 'i-refer' . '-settings', 'some_setting', 'default' );
 *   $my_settings = get_option( 'i-refer' . '-settings', 'default too' );
 * CMB2 Snippet: https://github.com/CMB2/CMB2-Snippet-Library/blob/master/options-and-settings-pages/theme-options-cmb.php
 */
?>
<div id="tabs-1" class="wrap">
  <?php
//			$cmb = new_cmb2_box(
//				array(
//					'id'         => 'i-refer' . '_options',
//					'hookup'     => false,
//					'show_on'    => array( 'key' => 'options-page', 'value' => array( 'i-refer' ) ),
//					'show_names' => true,
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'    => __( 'Text', 'i-refer' ),
//					'desc'    => __( 'field description (optional)', 'i-refer' ),
//					'id'      => 'text',
//					'type'    => 'text',
//					'default' => 'Default Text',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'    => __( 'Color Picker', 'i-refer' ),
//					'desc'    => __( 'field description (optional)', 'i-refer' ),
//					'id'      => 'colorpicker',
//					'type'    => 'colorpicker',
//					'default' => '#bada55',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Text Medium', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_textmedium',
//					'type' => 'text_medium',
//					// 'repeatable' => true,
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Website URL', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_url',
//					'type' => 'text_url',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Text Email', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_email',
//					'type' => 'text_email',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Time', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_time',
//					'type' => 'text_time',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Date Picker', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_textdate',
//					'type' => 'text_date',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Date Picker (UNIX timestamp)', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_textdate_timestamp',
//					'type' => 'text_date_timestamp',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Date/Time Picker Combo (UNIX timestamp)', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_datetime_timestamp',
//					'type' => 'text_datetime_timestamp',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'         => __( 'Test Money', 'i-refer' ),
//					'desc'         => __( 'field description (optional)', 'i-refer' ),
//					'id'           => '_textmoney',
//					'type'         => 'text_money',
//					'before_field' => '€', // Override '$' symbol if needed
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Text Area', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_textarea',
//					'type' => 'textarea',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Text Area for Code', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_textarea_code',
//					'type' => 'textarea_code',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Title Weeeee', 'i-refer' ),
//					'desc' => __( 'This is a title description', 'i-refer' ),
//					'id'   => '_title',
//					'type' => 'title',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'             => __( 'Test Select', 'i-refer' ),
//					'desc'             => __( 'field description (optional)', 'i-refer' ),
//					'id'               => '_select',
//					'type'             => 'select',
//					'show_option_none' => true,
//					'options'          => array(
//						'standard' => __( 'Option One', 'i-refer' ),
//						'custom'   => __( 'Option Two', 'i-refer' ),
//						'none'     => __( 'Option Three', 'i-refer' ),
//					),
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'             => __( 'Test Radio inline', 'i-refer' ),
//					'desc'             => __( 'field description (optional)', 'i-refer' ),
//					'id'               => '_radio_inline',
//					'type'             => 'radio_inline',
//					'show_option_none' => 'No Selection',
//					'options'          => array(
//						'standard' => __( 'Option One', 'i-refer' ),
//						'custom'   => __( 'Option Two', 'i-refer' ),
//						'none'     => __( 'Option Three', 'i-refer' ),
//					),
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'    => __( 'Test Radio', 'i-refer' ),
//					'desc'    => __( 'field description (optional)', 'i-refer' ),
//					'id'      => '_radio',
//					'type'    => 'radio',
//					'options' => array(
//						'option1' => __( 'Option One', 'i-refer' ),
//						'option2' => __( 'Option Two', 'i-refer' ),
//						'option3' => __( 'Option Three', 'i-refer' ),
//					),
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'     => __( 'Test Taxonomy Radio', 'i-refer' ),
//					'desc'     => __( 'field description (optional)', 'i-refer' ),
//					'id'       => '_text_taxonomy_radio',
//					'type'     => 'taxonomy_radio',
//					'taxonomy' => 'category', // Taxonomy Slug
//					// 'inline'  => true, // Toggles display to inline
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'     => __( 'Test Taxonomy Select', 'i-refer' ),
//					'desc'     => __( 'field description (optional)', 'i-refer' ),
//					'id'       => '_taxonomy_select',
//					'type'     => 'taxonomy_select',
//					'taxonomy' => 'category', // Taxonomy Slug
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'     => __( 'Test Taxonomy Multi Checkbox', 'i-refer' ),
//					'desc'     => __( 'field description (optional)', 'i-refer' ),
//					'id'       => '_multitaxonomy',
//					'type'     => 'taxonomy_multicheck',
//					'taxonomy' => 'category', // Taxonomy Slug
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Checkbox', 'i-refer' ),
//					'desc' => __( 'field description (optional)', 'i-refer' ),
//					'id'   => '_checkbox',
//					'type' => 'checkbox',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'    => __( 'Test Multi Checkbox', 'i-refer' ),
//					'desc'    => __( 'field description (optional)', 'i-refer' ),
//					'id'      => '_multicheckbox',
//					'type'    => 'multicheck',
//					'options' => array(
//						'check1' => __( 'Check One', 'i-refer' ),
//						'check2' => __( 'Check Two', 'i-refer' ),
//						'check3' => __( 'Check Three', 'i-refer' ),
//					),
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'    => __( 'Test wysiwyg', 'i-refer' ),
//					'desc'    => __( 'field description (optional)', 'i-refer' ),
//					'id'      => '_wysiwyg',
//					'type'    => 'wysiwyg',
//					'options' => array( 'textarea_rows' => 5 ),
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'Test Image', 'i-refer' ),
//					'desc' => __( 'Upload an image or enter a URL.', 'i-refer' ),
//					'id'   => '_image',
//					'type' => 'file',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'         => __( 'Multiple Files', 'i-refer' ),
//					'desc'         => __( 'Upload or add multiple images/attachments.', 'i-refer' ),
//					'id'           => '_file_list',
//					'type'         => 'file_list',
//					'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name' => __( 'oEmbed', 'i-refer' ),
//					'desc' => __( 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'i-refer' ),
//					'id'   => '_embed',
//					'type' => 'oembed',
//				)
//			);
//			$cmb->add_field(
//				array(
//					'name'         => 'Testing Field Parameters',
//					'id'           => '_parameters',
//					'type'         => 'text',
//					'before_row'   => '<p>before_row_if_2</p>', // Callback
//					'before'       => '<p>Testing <b>"before"</b> parameter</p>',
//					'before_field' => '<p>Testing <b>"before_field"</b> parameter</p>',
//					'after_field'  => '<p>Testing <b>"after_field"</b> parameter</p>',
//					'after'        => '<p>Testing <b>"after"</b> parameter</p>',
//					'after_row'    => '<p>Testing <b>"after_row"</b> parameter</p>',
//				)
//			);
//
//			cmb2_metabox_form( 'i-refer' . '_options', 'i-refer' . '-settings' );
			?>
		</div>
