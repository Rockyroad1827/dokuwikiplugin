<?php
/**
 * DokuWiki Plugin quickcreate (Action Component)
 *
 * @license    MIT
 */

if (!defined('DOKU_INC')) die();

class action_plugin_quickcreate extends DokuWiki_Action_Plugin {
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'add_script');
        $controller->register_hook('AJAX_CALL_UNKNOWN', 'BEFORE', $this, 'handle_ajax');
    }

    public function add_script(Doku_Event $event, $param) {
        global $INFO;
        $plugin_url = DOKU_BASE . 'lib/plugins/quickcreate/';
        $username = $INFO['userinfo']['name'] ?: 'Anonymous';

        echo '<link rel="stylesheet" type="text/css" href="'.$plugin_url.'style.css">';
        echo '<script>var QC_USERNAME = "'.$username.'";</script>';
        echo '<script src="'.$plugin_url.'script.js"></script>';
        
        echo '
        <div id="quickcreate-btn">
            <img src="'.$plugin_url.'newpage-icon.png" alt="Create Page">
        </div>
        <div id="quickcreate-overlay"></div>
        <div id="quickcreate-form">
            <div class="qc-header">Create New Page</div>
            <input type="text" id="qc-namespace" placeholder="Namespace (optional)">
            <input type="text" id="qc-pagename" placeholder="Page Name">
            <div class="qc-checkbox-container">
                Do not open the page after creation <input type="checkbox" id="qc-dont-redirect">
            </div>
            <button id="qc-create-btn">Create</button>
            <button id="qc-cancel-btn">Cancel</button>
        </div>
        <div id="quickcreate-success">
            <p>✅ Page created successfully!</p>
            <button id="qc-success-close">OK</button>
        </div>
        ';
    }

    public function handle_ajax(Doku_Event $event, $param) {
        if ($event->data != 'quickcreate_create_page') {
            return;
        }

        $event->preventDefault();
        $event->stopPropagation();

        global $INPUT, $INFO;

        $namespace = trim($INPUT->post->str('namespace'));
        $pagename = trim($INPUT->post->str('pagename'));
        $username = $INFO['userinfo']['name'] ?: 'Anonymous';
        $date = date("Y-m-d H:i:s");

        if (empty($pagename)) {
            echo json_encode(["success" => false, "error" => "Page name is required."]);
            return;
        }

        $fullPage = $namespace ? "$namespace:$pagename" : $pagename;
        $pageExists = page_exists($fullPage);

        if ($pageExists) {
            echo json_encode(["success" => false, "error" => "Page already exists."]);
            return;
        }

        $content = "**Page created by:** $username on $date\n\n";
        saveWikiText($fullPage, $content, "Created via QuickCreate Plugin");

        echo json_encode(["success" => true, "page" => $fullPage]);
    }
}
