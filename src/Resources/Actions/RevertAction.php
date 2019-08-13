<?php

namespace Yassir3wad\NovaAuditing\Resources\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use \OwenIt\Auditing\Models\Audit;

class RevertAction extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $this->revert($models->first());
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    private function revert(Audit $audit)
    {
        \DB::transaction(function () use ($audit) {
            $audit->auditable->transitionTo($audit, true)->save();
            $audit->old_values = [];
            $audit->new_values = [];
            $audit->save();
        });
    }
}
