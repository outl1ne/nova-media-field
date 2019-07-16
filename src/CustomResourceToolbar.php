<?php

namespace OptimistDigital\MediaField;
use Laravel\Nova\ResourceTool;

class CustomResourceToolbar extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Custom Detail Toolbar';
    }
    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'custom-index-toolbar';
    }
}