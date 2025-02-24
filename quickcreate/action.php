<?php

if (!defined('DOKU_INC')) define('DOKU_INC', realpath(__DIR__ . '/../../..') . '/');
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

class action_plugin_quickcreate extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'addButton');
    }

    public function addButton(Doku_Event &$event, $param) {
        $event->data['script'][] = [
            'type' => 'text/javascript',
            'charset' => 'utf-8',
            'src' => DOKU_BASE . 'lib/plugins/quickcreate/script.js'
        ];
        $event->data['link'][] = [
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => DOKU_BASE . 'lib/plugins/quickcreate/style.css'
        ];
    }
}
