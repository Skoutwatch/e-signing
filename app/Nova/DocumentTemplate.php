<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DocumentTemplate extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\DocumentTemplate::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            Text::make('title')->sortable()->rules('required', 'max:255'),

            Text::make('File')->rules('required'),

            // File::make('File')->rules('required')->disk('s3')->thumbnail(function ($value, $disk) {
            //     return $value;
            // })->preview(function ($value, $disk) {
            //     return $value;
            // })->displayUsing(function ($value) {
            //     return  base64_encode($value);
            // }),

            Select::make('role')
                ->options([
                    'CFO' => 'CFO',
                    'Notary' => 'Notary',
                ])->rules('required'),

            MorphTo::make('Document Template', 'templatable')->types([
                Plan::class,
            ])->nullable()->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('templatable_type', null)->where('templatable_id', null)->latest()->get();
    }
}
