<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://devcrazy.com
 * @since      1.0.0
 *
 * @package    Cfwjm
 * @subpackage Cfwjm/admin/partials
 */
?>
<div class="form-wrap">    
    <h2><?php echo __('Add Field', $this->plugin_name); ?></h2>
    <?php echo $_SESSION['cfwjm_msg']; $_SESSION['cfwjm_msg'] = ''; ?>
    <form method="POST" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
        <?php
            wp_nonce_field( $action, $nounce_name );
            // wp_nonce_field( 'cfwjm_add_field', 'cfwjm_add_message' );
        ?>
        <?php if (isset($id)) { ?>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        <?php } ?>
        <input type="hidden" name="action" value="<?php echo $action; ?>">
        <div class="form-field form-required label-wrap">
            <label for="tag-field-key"><?php echo __("Key", $this->plugin_name); ?></label>
            <input name="tag-field-key" type="text" value="<?php echo $val['field_key']; ?>" required>
            <p>Key must me unique</p>
        </div>
        <div class="form-field form-required label-wrap">
            <label for="tag-label"><?php echo __("Label", $this->plugin_name); ?></label>
            <input name="tag-label" type="text" value="<?php echo $val['label']; ?>" required>
            <p>The label appears before the field</p>
        </div>
        <div class="form-field">
            <label for="tag-type"><?php echo __("Type", $this->plugin_name); ?></label>
            <select name="tag-type" style="min-width: 95%;" data-value="<?php echo $val['type']; ?>" required>
                <option value="date">Date</option>
                <option value="radio">Radio</option>
                <option value="select">Select</option>
                <option value="text">Text</option>
                <option value="checkbox">Check Box</option>
                <option value="tags">Tag Input</option>
            </select>
            <p>This is the type of the field</p>
        </div>
        <div class="form-field form-required label-wrap type_meta" style="display: none;">
            <label for="tag-meta"><?php echo __("Items", $this->plugin_name); ?></label>
            <input name="tag-meta" type="text" data-role="tagsinput" value="<?php echo $val['meta_1']; ?>">
            <p>Please input items seperated by comma ","</p>
        </div>
        <div class="form-field">
            <label for="tag-placeholder"><?php echo __("Placeholder", $this->plugin_name); ?></label>
            <input name="tag-placeholder" type="text" value="<?php echo $val['placeholder']; ?>">            
        </div>
        <div class="form-field">
            <label for="tag-priority"><?php echo __("Priority", $this->plugin_name); ?></label>
            <select name="tag-priority" style="min-width: 95%;" data-value="<?php echo $val['priority']; ?>">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
            </select>
        </div>
        <div class="form-field">
            <label for="tag-required"><?php echo __("Required", $this->plugin_name); ?></label>
            <select name="tag-required" style="min-width: 95%;" data-value="<?php echo $val['required']; ?>">
                <option value="1">True</option>
                <option value="0">False</option>
            </select>
        </div>
        <div class="form-field">
            <label for="tag-description"><?php echo __("Description", $this->plugin_name); ?></label>
            <input name="tag-description" type="text" value="<?php echo $val['description']; ?>">
        </div>
        <div class="form-field">
            <label for="tag-cfwjm-tag"><?php echo __("Cfwjm Tag", $this->plugin_name); ?></label>
            <input name="tag-cfwjm-tag" type="text" value="<?php echo $val['cfwjm_tag']; ?>">
        </div>
        <?php
            submit_button( esc_attr__( 'Submit', $this->plugin_name ), 'primary', 'submit-name', TRUE );
        ?>
    </form>
</div>