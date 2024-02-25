<?php

namespace App\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

// use \App\Nova\Auth;

class DocumentUpload extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\DocumentUpload::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Select::make('Document Status', 'status')
                ->options([
                    'Cancelled' => 'Cancelled',
                    'Completed' => 'Completed',
                    'In-view' => 'In-view',
                    'New' => 'New',
                ])->rules('required'),

            File::make('File Url')->disk('s3'),

            Select::make('Display', 'display')->options([
                true => 'True',
                false => 'False',
            ])->rules('required')->hidefromIndex(),

            BelongsTo::make('Document')->searchable()->hideWhenUpdating(),

            BelongsTo::make('User')->hideWhenUpdating()->searchable(),
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

    public function storagePath()
    {
        return config('upload.folder').'/'.strtolower('DocumentUpload')."/{$this->id}/";
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereHas('document', function ($q) {
            $q->where('display', '=', false)->where('is_a_template', '=', false)->where('is_a_signlink_docs', '=', false);
            // })->whereHas('document.scheduleSession', function ($q) {
            //     $q->where('type', '=', 'Request Affidavit')->orWhere('type', '=', 'Request A Notary');
        })->whereHas('document.scheduleSession.transactions', function ($q) {
            $q->where('status', '=', 'Paid');
        })->latest()->get();
    }
}
