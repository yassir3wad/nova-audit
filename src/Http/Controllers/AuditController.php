<?php

namespace Yassir3wad\NovaAuditing\Http\Controllers;


use App\Models\Post;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Laravel\Nova\Contracts\ListableField;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\ResourceToolElement;
use \OwenIt\Auditing\Models\Audit;

class AuditController extends BaseController
{
    public function fields(NovaRequest $request, Audit $audit)
    {
        $changes = array_keys($audit->getModified());

        $dirtyLatestArticleFromOld = $audit->auditable->transitionTo($audit, true);

        $previous = new \App\Nova\Post($dirtyLatestArticleFromOld);
        $current = new \App\Nova\Post(Post::find($audit->auditable_id));

        return response()->json([
            'previous' => $this->filter($previous->detailFields($request))->values()->all(),
            'current' => $this->filter($current->detailFields($request))->values()->all(),
            'changes' => $changes
        ]);
    }

    private function filter(Collection $collection)
    {
        return $collection->reject(function ($field) {
            return $field instanceof ListableField ||
                $field instanceof ResourceToolElement ||
                $field->attribute === 'ComputedField' ||
                ($field instanceof ID);
        });
    }
}
