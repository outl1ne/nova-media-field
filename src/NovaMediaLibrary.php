<?php


namespace OptimistDigital\MediaField;

use Laravel\Nova\Tool;

class NovaMediaLibrary extends Tool {

    public function renderNavigation() {
        return view('nova-media::navigation');
    }

}