<?php

namespace App\Nova;

use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Sessions extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\ScheduleSession::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        "{$this->title} - {$this->user->first_name} {$this->user->last_name}";
    }

    /**
     * Default ordering for index query.
     *
     * @var array
     */
    public static $sort = [
        'created_at' => 'asc',
    ];

    public static $search = [
        'id',
        'title',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),
            Text::make('Title'),
            Text::make('description')->sortable()->rules('required', 'max:255')->hideFromIndex(),
            Select::make('request type')
                ->options([
                    'Custom' => 'Custom',
                    'Template' => 'Template',
                    'Upload' => 'Upload',
                    'Virtual' => 'Virtual',
                ])->rules('required')->hideFromIndex(),

            Text::make('type')->sortable()->rules('max:255')->hideFromIndex()->nullable(),

            Text::make('session')->sortable()->rules('max:255')->hideFromIndex()->nullable(),

            Select::make('Delivery Channel')->options([
                'Email' => 'Email',
                'Address' => 'Address',
                'Both' => 'Email and Address',
            ])->rules('required')->hideFromIndex(),

            Text::make('Delivery Address')->nullable()->rules('max:255')->hideFromIndex(),
            Text::make('Delivery Email')->sortable()->rules('max:255')->hideFromIndex(),
            BelongsTo::make('User', 'User'),
            Date::make('Date', 'created_at')
                ->displayUsing(fn ($value) => $value ? $value->format('d/m/Y , g:ia') : '')->sortable()->rules('required', 'max:255'),
            Text::make('Set Reminder In Minutes')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('Start time')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('End Time')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('Token')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('Meeting Link')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('Start Session')->sortable()->rules('max:255')->hideFromIndex(),
            Text::make('End Session')->sortable()->rules('max:255')->hideFromIndex(),
            Select::make('Status', 'status')
                ->options([
                    'Cancelled' => 'Cancelled',
                    'Completed' => 'Completed',
                    'In-view' => 'In-view',
                    'New' => 'New',
                ])->rules('required'),
            Text::make('Recordings', function () {
                $recordings = $this->scheduleSessionRecordings->pluck('video_recording_file')
                    ->implode(', ');

                return $recordings;
            })->rules('required'),
            DependencyContainer::make([
                Textarea::make('Reason For Cancellation', 'comment')->rules('required'),
            ])->dependsOn('status', 'Cancelled'),

            MorphTo::make('Request Type', 'schedule')->types([
                Document::class,
            ])->nullable()->hideFromIndex(),
            Select::make('notary id')
                ->options(User::where('role', 'Notary')->pluck('first_name', 'id'))->hideFromIndex(),
        ];
    }

    public function cards(NovaRequest $request)
    {
        return [];
    }

    public function filters(NovaRequest $request)
    {
        return [];
    }

    public function lenses(NovaRequest $request)
    {
        return [];
    }

    public function actions(NovaRequest $request)
    {
        return [];
    }

    public static function label()
    {
        return 'Sessions with recording';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->whereHas('transactions', function ($q) {
            $q->where('status', '=', 'Paid');
        })->get();
    }
}
