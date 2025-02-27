<?php
if (!defined('DOKU_INC')) die();

class admin_plugin_loggedpages extends DokuWiki_Admin_Plugin {

    public function forAdminOnly() {
        return true;
    }

    public function getMenuText($language) {
        return 'Logged Pages';
    }

    public function handle() {
        if (isset($_POST['delete']) && !empty($_POST['log_entries'])) {
            $this->delete_logs($_POST['log_entries']);
        } elseif (isset($_POST['export']) && !empty($_POST['log_entries'])) {
            $this->export_logs($_POST['log_entries']);
        }
    }

    public function html() {
        echo '<h2>Page Edit Logs</h2>';
        $logfile = DOKU_PLUGIN . 'loggedpages/logs.txt';

        if (!file_exists($logfile)) {
            echo '<p>No logs found.</p>';
            return;
        }

        $logs = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!$logs) {
            echo '<p>No logs available.</p>';
            return;
        }

        echo '<form method="post">';
        echo '<table border="1"><tr><th>Select</th><th>Action</th><th>Page</th><th>Timestamp</th><th>Previous Content</th></tr>';
        foreach ($logs as $index => $log) {
            $logData = json_decode($log, true);
            if ($logData) {
                echo '<tr>';
                echo '<td><input type="checkbox" name="log_entries[]" value="' . $index . '"></td>';
                echo '<td>' . htmlspecialchars($logData['action']) . '</td>';
                echo '<td>' . htmlspecialchars($logData['page']) . '</td>';
                echo '<td>' . date('Y-m-d H:i:s', $logData['timestamp']) . '</td>';
                echo '<td><pre>' . htmlspecialchars($logData['previous_content'] ?? 'N/A') . '</pre></td>';
                echo '</tr>';
            }
        }
        echo '</table>';
        echo '<input type="submit" name="delete" value="Delete Selected">';
        echo '<input type="submit" name="export" value="Export Selected">';
        echo '</form>';
    }

    private function delete_logs($entries) {
        $logfile = DOKU_PLUGIN . 'loggedpages/logs.txt';
        $logs = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($entries as $index) {
            unset($logs[$index]);
        }

        file_put_contents($logfile, implode(PHP_EOL, $logs) . PHP_EOL);
    }

    private function export_logs($entries) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="exported_logs.txt"');
        $logfile = DOKU_PLUGIN . 'loggedpages/logs.txt';
        $logs = file($logfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($entries as $index) {
            echo $logs[$index] . PHP_EOL;
        }
        exit;
    }
}
