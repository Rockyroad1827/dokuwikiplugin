<?php
/**
 * DokuWiki Plugin quicknewpage (Action Component)
 *
 * @license    MIT
 * @author     Your Name
 */

if (!defined('DOKU_INC')) die();

class action_plugin_quicknewpage extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'inject_scripts');
    }

    public function inject_scripts(Doku_Event $event, $param) {
        $event->data['script'][] = [
            'type' => 'text/javascript',
            'src' => DOKU_BASE . 'lib/plugins/quicknewpage/script.js',
            'defer' => 'defer'
        ];

        $event->data['link'][] = [
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => DOKU_BASE . 'lib/plugins/quicknewpage/style.css'
        ];
    }
}
?>
