<?php

namespace App\Nova;

use App\Models\Document as DocumentModel;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Document extends Resource
{
    public static $model = DocumentModel::class;

    public function title()
    {
        return "{$this->scheduleSession?->type} - {$this->title}";
    }

    public static $search = [
        'id',
        'title',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            Text::make('title')->sortable()->rules('required', 'max:255'),

            BelongsTo::make('User')->sortable(),

            HasMany::make('Document Uploads', 'documentUploads'),

            MorphTo::make('Document Model', 'documentable')->types([
                Plan::class,
                Transaction::class,
            ])->nullable()->hideFromIndex(),

            Select::make('Status', 'status')
                ->options([
                    'Cancelled' => 'Cancelled',
                    'Completed' => 'Completed',
                    'In-view' => 'In-view',
                    'New' => 'New',
                ])->rules('required'),

            Select::make('Public', 'public')->options([
                true => 'True',
                false => 'False',
            ])->rules('required')->hidefromIndex(),

            Select::make('Template', 'is_a_signlink_docs')->options([
                true => 'True',
                false => 'False',
            ])->rules('required')->hidefromIndex(),

            Select::make('Template', 'is_a_signlink_docs')->options([
                true => 'True',
                false => 'False',
            ])->rules('required')->hidefromIndex(),

            MorphMany::make('ScheduleSession', 'scheduleSessions', ScheduleSession::class)->singularLabel('scheduleSession'),
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
        return $query->whereHas('scheduleSession', function ($q) {
            $q->where('type', '=', 'Request Affidavit')->orWhere('type', '=', 'Request A Notary');
        })->whereHas('scheduleSession.transactions', function ($q) {
            $q->where('status', '=', 'Paid');
        })->latest()->get();
    }
}
