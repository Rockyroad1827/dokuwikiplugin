<?php
if (!defined('DOKU_INC')) die();

class action_plugin_discordwebhook extends DokuWiki_Action_Plugin {
    private static $lastEventTime = 0; // Store the last event timestamp

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('IO_WIKIPAGE_WRITE', 'AFTER', $this, 'handle_page_change');
        $controller->register_hook('IO_WIKIPAGE_UNLINK', 'BEFORE', $this, 'handle_page_deletion');
    }

    private function isCooldownActive() {
        $currentTime = time();
        if ($currentTime - self::$lastEventTime < 1) {
            return true; // Still in cooldown
        }
        self::$lastEventTime = $currentTime; // Update last event time
        return false;
    }

    public function handle_page_change(Doku_Event $event) {
        if (!$this->getConf('webhook_enabled')) return; // Check if webhook is enabled
        if ($this->isCooldownActive()) return; // Skip event if cooldown is active

        global $ID;
        $webhook_url = $this->getConf('webhook_url');
        if (empty($webhook_url)) return; // Exit if no webhook is set

        $pageName = noNS($ID);
        $namespace = getNS($ID);
        $url = wl($ID, '', true, '&');

        if ($event->data[3]) {
            $messageType = "✏️ Page Edited";
            $color = 16776960; // Yellow
        } else {
            $messageType = "🆕 Page Created";
            $color = 5814783; // Blue
        }

        $message = [
            'username'   => 'DokuWiki Bot',
            'embeds' => [[
                'title'       => $messageType,
                'description' => "**Page:** [$pageName]($url)\n**Namespace:** " . ($namespace ?: 'None'),
                'color'       => $color
            ]]
        ];

        $this->sendToDiscord($webhook_url, $message);
    }

    public function handle_page_deletion(Doku_Event $event) {
        if (!$this->getConf('webhook_enabled')) return; // Check if webhook is enabled
        if ($this->isCooldownActive()) return; // Skip event if cooldown is active

        $webhook_url = $this->getConf('webhook_url');
        if (empty($webhook_url)) return;

        $pageID = $event->data;
        $pageName = noNS($pageID);
        $namespace = getNS($pageID);

        $messageType = "🗑️ Page Deleted";
        $color = 16711680; // Red

        $message = [
            'username'   => 'DokuWiki Bot',
            'embeds' => [[
                'title'       => $messageType,
                'description' => "**Namespace:** " . ($namespace ?: 'None')."\n**Page:** $pageName",
                'color'       => $color
            ]]
        ];

        $this->sendToDiscord($webhook_url, $message);
    }

    private function sendToDiscord($url, $data) {
        $json_data = json_encode($data);
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => $json_data,
            ],
        ];
        $context = stream_context_create($options);
        file_get_contents($url, false, $context);
    }
}
?>
