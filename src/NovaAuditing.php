<?php

namespace Yassir3wad\NovaAuditing;

use Laravel\Nova\ResourceTool;

class NovaAuditing extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Nova Auditing';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'nova-auditing';
    }
}
