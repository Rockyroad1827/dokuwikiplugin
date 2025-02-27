<?php
if (!defined('DOKU_INC')) die();

class syntax_plugin_loggedpages extends DokuWiki_Syntax_Plugin {

    public function getType() { return 'substition'; }
    public function getSort() { return 99; }
    public function connectTo($mode) { $this->Lexer->addSpecialPattern('{{loggedpages}}', $mode, 'plugin_loggedpages'); }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return []; // Required method, but no special handling needed
    }

    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;
        
        $logfile = DOKU_PLUGIN . 'loggedpages/logs.txt';
        if (!file_exists($logfile)) {
            $renderer->doc .= '<p>No logs found.</p>';
            return true;
        }

        $logs = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$logs) {
            $renderer->doc .= '<p>No logs available.</p>';
            return true;
        }

        $renderer->doc .= '<ul>';
        foreach ($logs as $log) {
            $logData = json_decode($log, true);
            if ($logData) {
                $renderer->doc .= '<li><strong>' . htmlspecialchars($logData['action']) . '</strong> - ' .
                    htmlspecialchars($logData['page']) . ' at ' .
                    date('Y-m-d H:i:s', $logData['timestamp']) .
                    '<pre>' . htmlspecialchars($logData['previous_content'] ?? 'N/A') . '</pre>' .
                    '</li>';
            } else {
                $renderer->doc .= '<li>Invalid log entry: ' . htmlspecialchars($log) . '</li>';
            }
        }
        $renderer->doc .= '</ul>';

        return true;
    }
}