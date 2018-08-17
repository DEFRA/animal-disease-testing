<?php
// updates for the debug bar
if (class_exists('Debugbar')) {
    Debugbar::addCollector(new \DebugBar\DataCollector\APIRequestCollector());
    // Debugbar::disable();
}