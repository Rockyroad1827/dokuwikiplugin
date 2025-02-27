<?php
if (!defined('DOKU_INC')) die();

class action_plugin_loggedpages extends DokuWiki_Action_Plugin {
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('IO_WIKIPAGE_WRITE', 'AFTER', $this, 'log_page_changes');
    }

    public function log_page_changes(Doku_Event $event) {
        global $ID;
        
        $logfile = DOKU_PLUGIN . 'loggedpages/logs.txt';
        $page = $event->data['id']; // Retrieve the page ID
        $action = ($event->data['changeType'] === DOKU_CHANGE_TYPE_DELETE) ? 'deleted' : 'edited';
        $timestamp = time();
        
        // Get previous content before changes
        $revisions = page_revisions($page, 0, 1);
        $lastRevision = $revisions[0] ?? null;
        $oldContent = $lastRevision ? rawWiki($page, $lastRevision) : 'N/A';
        
        $logEntry = json_encode([
            'action' => $action,
            'page' => $page,
            'timestamp' => $timestamp,
            'previous_content' => $oldContent
        ]);
        
        file_put_contents($logfile, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
