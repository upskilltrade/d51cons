<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Planning = 'planning';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return __('site.project_status.'.$this->value);
    }
}
