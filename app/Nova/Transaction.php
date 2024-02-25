<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Transaction extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Transaction::class;

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
        'id',
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

            Select::make('status')
                ->options([
                    'Pending' => 'Pending',
                    'Failed' => 'Failed',
                    'Paid' => 'Paid',
                ])
                ->rules('required'),

            MorphTo::make('Transaction Type', 'transactionable')->types([
                Plan::class,
                ScheduleSession::class,
            ])->nullable()->hideFromIndex(),

            BelongsTo::make('User'),

            Number::make('Subtotal')->sortable()->rules('min:255')->showOnDetail(),

            Number::make('Total')->min(1)->step(0.01),

            Number::make('Amount Paid')->min(1)->step(0.01),

            Number::make('Charges')->onlyOnDetail(),

            Text::make('Payment Reference')->onlyOnDetail(),

            Text::make('Currency')->sortable()->hideFromIndex()->showOnDetail()->hideWhenCreating(),

            Text::make('Payment Gateway')->onlyOnDetail(),

            Text::make('Payment Gateway Method')->onlyOnDetail(),

            Text::make('Payment Gateway Message')->onlyOnDetail(),

            Code::make('Payment Gateway Json Response')->onlyOnDetail(),

            Number::make('Payment Gateway Charge')->onlyOnDetail(),

            Number::make('Discount Applied')->hideFromIndex()->showOnDetail(),

            Number::make('Discount Amount')->hideFromIndex()->showOnDetail(),

            Select::make('Platform Initiated')
                ->options([
                    'Web' => 'Web',
                    'Admin' => 'Admin',
                ])
                ->rules('required')->hideWhenUpdating(),

            Select::make('Success')
                ->options([
                    true => 'True',
                    false => 'False',
                ])
                ->rules('required')->hideWhenUpdating(),

            Date::make('Created At')->onlyOnDetail(),

            Date::make('Updated At')->sortable()->hideFromIndex()->showOnDetail()->hideWhenCreating(),

            Date::make('Deleted At')->onlyOnDetail(),

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
}
