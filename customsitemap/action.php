<?php

if (!defined('DOKU_INC')) die();

class action_plugin_customsitemap extends DokuWiki_Action_Plugin {
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'handle_sitemap_override');
    }

    public function handle_sitemap_override(Doku_Event $event) {
        if ($event->data !== 'sitemap') return;
            event->preventDefault();
        $event->stopPropagation();
        
        global $ID;
        $sitemapData = $this->generateSitemap();
        
        echo "<h2>Custom Sitemap Loaded</h2>"; // Debug message
        echo '<div id="custom-sitemap"></div>';
        echo '<script>console.log("Sitemap Data:", ' . json_encode($sitemapData) . ');</script>';
        echo '<script>var sitemapData = ' . json_encode($sitemapData) . ';</script>';
        echo '<script src="' . DOKU_BASE . 'lib/plugins/customsitemap/sitemap.js"></script>';
        echo '<link rel="stylesheet" href="' . DOKU_BASE . 'lib/plugins/customsitemap/sitemap.css">';
    }
        

    private function generateSitemap() {
        $data = [];
        $ns = $this->getNamespaces('');
        foreach ($ns as $namespace) {
            $data[] = [
                'name' => trim($namespace, ':'),
                'pages' => $this->getPagesInNamespace($namespace)
            ];
        }
        return $data;
    }

    private function getNamespaces($base) {
        $namespaces = [];
        $dir = utf8_encode(str_replace(':', '/', $base));
        $folders = glob(DOKU_INC . 'data/pages/' . $dir . '/*', GLOB_ONLYDIR);
        foreach ($folders as $folder) {
            $namespaces[] = str_replace(DOKU_INC . 'data/pages/', '', $folder);
        }
        return $namespaces;
    }

    private function getPagesInNamespace($namespace) {
        $pages = []; 
        $dir = utf8_encode(str_replace(':', '/', $namespace));
        $files = glob(DOKU_INC . 'data/pages/' . $dir . '/*.txt');
        foreach ($files as $file) {
            $pages[] = cleanID(str_replace(DOKU_INC . 'data/pages/', '', $file));
        }
        return $pages;
    }
}
