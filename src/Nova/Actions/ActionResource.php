<?php

namespace GeneaLabs\NovaMapMarkerField\Nova\Actions;

use App\Models\ActionEvent;

class ActionResource extends \Laravel\Nova\Actions\ActionResource
{
    public static $model = ActionEvent::class;
}
