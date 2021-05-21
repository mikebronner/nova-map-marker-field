<?php

namespace GeneaLabs\NovaMapMarkerField\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ActionEvent extends \Laravel\Nova\Actions\ActionEvent
{

    /**
     * Create a new action event instance for a resource update.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Laravel\Nova\Actions\ActionEvent
     */
    public static function forResourceUpdate($user, $model)
    {
        return new static([
            'batch_id' => (string) Str::orderedUuid(),
            'user_id' => $user->getAuthIdentifier(),
            'name' => 'Update',
            'actionable_type' => $model->getMorphClass(),
            'actionable_id' => $model->getKey(),
            'target_type' => $model->getMorphClass(),
            'target_id' => $model->getKey(),
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->getKey(),
            'fields' => '',
            'original' => array_intersect_key($model->toArray(), $model->getDirty()),
            'changes' => $model->getDirty(),
            'status' => 'finished',
            'exception' => '',
        ]);
    }
}
