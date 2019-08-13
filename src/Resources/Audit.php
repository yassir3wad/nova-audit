<?php

namespace Yassir3wad\NovaAuditing\Resources;

use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Resource;
use Orlyapps\NovaMultilineText\MultilineText;
use Yassir3wad\NovaAuditing\NovaAuditing;
use Yassir3wad\NovaAuditing\Resources\Actions\RevertAction;

class Audit extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \OwenIt\Auditing\Models\Audit::class;

    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'event';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'event', 'old_values', 'new_values', 'url', 'ip_address', 'user_agent', 'created_at'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make("Event"),

            MorphTo::make('By', 'user')->types(config('novaaudit.user_resources')),

            MultilineText::make("Changes On", function () {
                return array_keys($this->resource->getModified());
            })->highlightFirst(false),

            MorphTo::make('Auditable')->types(config('novaaudit.auditable_resources')),

            Text::make("Url")->onlyOnDetail(),

            Text::make("IP", "ip_address")->onlyOnDetail(),

            Text::make("User Agent")->onlyOnDetail(),

            DateTime::make("Date", "created_at"),

            NovaAuditing::make(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new RevertAction())->canRun(function () {
                return true;
            })
        ];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }
}
