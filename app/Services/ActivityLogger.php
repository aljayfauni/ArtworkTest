<?php

namespace App\Services;

use App\Enums\ActivityAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        ActivityAction $action,
        Model $model,
        array $properties = []
    ): void {
        $lineNo = $properties['line_no'] ?? null;

        // 1. Check if a remark was explicitly passed in the properties array.
        // 2. If not, check if the model itself had its remark updated just now.
        $remarks = $properties['remarks'] 
            ?? ($model->wasChanged('remarks') ? $model->remarks : null);

        activity($model->getTable())
            ->performedOn($model)
            ->causedBy(Auth::user())
            ->withProperties(array_merge([
                'action'       => $action->value,
                'action_label' => $action->label($lineNo),
                'remarks'      => $remarks, 
            ], $properties))
            ->log($action->label($lineNo));
        }
}
