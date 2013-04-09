<div class="wrap nix-antispam">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e( $this->plugin_name , 'nix' ); ?></h2>

    <form method="post" class="nix-antispam-form">
        <table class="form-table">
            <tr valign="top">
                <td>
                    <?php _e( 'Activate Antispam:', 'nix' );?>
                </td>
                <td>
                    <label>
                        <input type="checkbox" name="nix_antispam_options[active]" value="1" <?php checked( $this->options[0]['active'], 1 ); ?> />
                       <?php _e( 'On / Off', 'nix' ); ?>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <label> <?php _e( 'Your secret key 1', 'nix' ); ?>:</label>
                </td>
                <td>
                    <input type="text" name="nix_antispam_options[secret_keys][sk_1_name]" value="<?php echo $this->options[0]['secret_keys']['sk_1_name']; ?>" size="40"/>
                    <br/>
                    <small><?php _e( 'Use the Latin alphabet and numbers', 'nix' ); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <label> <?php _e( 'Your secret key 2' ); ?>:</label>
                </td>
                <td>
                    <input type="text" name="nix_antispam_options[secret_keys][sk_2_hash]" value="<?php echo $this->options[0]['secret_keys']['sk_2_hash']; ?>" size="40"/>
                    <br/>
                    <small><?php _e( 'Use the Latin alphabet and numbers', 'nix' ); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <label> <?php _e( 'Your secret key 3', 'nix' ); ?>:</label>
                </td>
                <td>
                    <input type="text" name="nix_antispam_options[secret_keys][sk_3_data]" value="<?php echo $this->options[0]['secret_keys']['sk_3_data']; ?>" size="40"/>
                    <br/>
                    <small><?php _e( 'Use the Latin alphabet and numbers', 'nix' ); ?></small>
                </td>
            </tr>
            <tr valign="top">
                <td>
                    <label> <?php _e( 'Your secret key 4', 'nix' ); ?>:</label>
                </td>
                <td>
                    <input type="text" name="nix_antispam_options[secret_keys][sk_4_data_repalce]" value="<?php echo $this->options[0]['secret_keys']['sk_4_data_repalce']; ?>" size="40"/>
                    <br/>
                    <small><?php _e( 'Use the Latin alphabet and numbers', 'nix' ); ?></small>
                </td>
            </tr>
        </table>
        <p class="submit">
            <button type="submit" name="nix_antispam_submit" id="submit" class="button button-primary" ><?php _e( 'Save Changes', 'nix' ); ?></button>
        </p>
    </form>

    <?php include( $this->get_template_path() .'/about-us.php' ); ?>

</div><!-- .wrap -->