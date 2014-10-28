<?php
/*
Plugin Name: Nix Anti-Spam light
Description: Anti-Spam protection for comments.
Author: NIX Solutions Ltd
Author URI: http://nixsolutions.com/departments/cms/
Version: 0.0.4
*/

class NFASL_Antispam_Light {

    protected $plugin_dir_path;
    protected $nfas_secret_keys;
    protected $options;

    public $plugin_name = 'NIX Anti-Spam Light';

    function __construct() {

        $this->plugin_dir_path = plugin_dir_path( __FILE__ );

        require_once $this->plugin_dir_path . '/messages.class.php';
        NFASL_Messages::init();

        // update options
        if ( isset( $_POST['nix_antispam_options'] ) ) {
            $update_val_options = $_POST['nix_antispam_options'];

            foreach ( $update_val_options['secret_keys'] as $key => $value) {
                $update_val_options['secret_keys'][$key] =  ( empty( $value ) ) ?  $this->generate_secret_key() : $value ;
            }
            update_option( 'nix_antispam_options', array( $update_val_options ) );
        }

        $this->options = get_option( 'nix_antispam_options' );

        if ( $this->options[0]['active'] == 1 ) {
            add_action( 'comment_form', array( $this,'nfas_comment_form' ), 10, 1 );
            add_filter( 'pre_comment_approved', array( $this, 'nfas_pre_comment_approved' ) , '99', 2 );
        }

        // add to admin menu
        add_action( 'admin_menu', array( $this,'add_admin_menu' ) );

        // plugin settings
        register_activation_hook( __FILE__, array( $this, 'activate' )  );

        if ( is_admin() && strrpos($_SERVER['REQUEST_URI'], basename( __FILE__) ) )
            $this->init();

        // @todo : imprement or remove these rows
        //register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        //register_uninstall_hook( __FILE__ , array( $this, 'uninstall' ) );
    }

    public function get_template_path() {
        return $this->plugin_dir_path .'template';
    }

    // Activate plugin and update default option
    public function activate() {

        // delete_option('nf_c_a_options');
        if ( get_option( 'nix_antispam_options' ) == false ) {
            $default_options = array(
                                     'active'     => 1,
                                     'secret_keys' => array(
                                                            'sk_1_name'         => $this->generate_secret_key(),
                                                            'sk_2_hash'         => $this->generate_secret_key(),
                                                            'sk_3_data'         => $this->generate_secret_key(),
                                                            'sk_4_data_repalce' => $this->generate_secret_key()
                                                    )
                                    );
            update_option( 'nix_antispam_options', array( $default_options ) );
        }
    }

    // Notice in plugin options page
    public function admin_help_notice() {
        global $current_screen;
        if ( $current_screen->base == 'settings_page_'. basename( __FILE__,'.php' ) ) {
            return true;
        }
    }

    // define('NFAS_SECRET_KEY', 'NIX_SITE_SECRET_KEY_548');
    public function nfas_comment_form( $post_id ) {
        $data = base64_encode( serialize( array(
                                'post_id' => $post_id,
                                'microtime' => microtime( true )
                              ) ) );
        ?>
        <input type="hidden" name="<?php echo $this->options[0]['secret_keys']['sk_1_name'] ?>" value="<?php echo $data; ?>" />
        <input type="hidden" name="<?php echo $this->options[0]['secret_keys']['sk_2_hash'] ?>" value="<?php echo md5( $this->options[0]['secret_keys']['sk_3_data'] . $data); ?>" />
        <script type="text/javascript">
            var elems = document.getElementsByTagName('input');
            for (var i = elems.length-1; i > -1; i--){
                if (elems[i].getAttribute('name') == '<?php echo $this->options[0]['secret_keys']['sk_1_name'] ?>') {
                    elems[i].setAttribute('name', '<?php echo $this->options[0]['secret_keys']['sk_4_data_repalce'] ?>');
                }
            }
        </script>
        <?php
    }

    // Generate secret Key
    protected function generate_secret_key($length = 25){
        $num = range(0 , 9);
        $alf = range( 'a', 'z' );
        $_alf = range( 'A', 'Z' );
        $symbols = array_merge( $alf , $_alf , $num );
        shuffle( $symbols );
        $code_array = array_slice( $symbols, 0, (int)$length );
        $code = implode( "", $code_array );
        return $code;
    }

    public function nfas_pre_comment_approved( $approved, $commentdata ) {
        $data = $_REQUEST['nfas_data'];
        if (md5( $this->nfas_secret_key . $data) !== $_REQUEST['nfas_hash'] ) {
            $approved = 'spam';
        }
        else {
            $data_A = unserialize(base64_decode($data));
            if (! is_array($data_A)) {
                $approved = 'spam';
            }
            else {
                if ($_REQUEST['comment_post_ID'] != $data_A['post_id']) {
                    $approved = 'spam';
                }
                else {
                    $time_delay = microtime(true) - $data_A['microtime'];
                    if ($time_delay < 4) {
                        $approved = 'spam';
                    }
                }
            }
        }
        return $approved;
    }

    // Create plugin option settings menu
    public function add_admin_menu() {
        // settings menu page
        add_options_page( $this->plugin_name , $this->plugin_name, 'manage_options', basename( __FILE__ ), array( $this,'view_options_page' ) );
    }

    // Create page option
    public function view_options_page() {

        include( $this->get_template_path() .'/main-options-page.php' );
    }

    // init style and scripts
    private function init() {
        wp_enqueue_script( 'nfasl-main-script', plugins_url( '/js/main.js', __FILE__ ), array('jquery') );
        wp_enqueue_style( 'nfasl-main-style', plugins_url( '/css/style.css', __FILE__ ) );
   }

}// Class

global $nfasl;
$nfasl = new NFASL_Antispam_Light();
