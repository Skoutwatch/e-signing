<?php

namespace App\Nova;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Plan extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Plan::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->trial == true ? "{$this->name} - Trial" : "{$this->name} - {$this->amount}";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'type',
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

            Text::make('name')->sortable()->rules('required', 'max:255'),

            Select::make('type')
                ->options([
                    'Subscription' => 'Subscription',
                    'Packs' => 'Notary Packs',
                ]),

            Boolean::make('Trial'),

            Select::make('teams')
                ->options([
                    true => 'true',
                    false => 'false',
                ])->rules('required')->hideFromIndex(),

            Select::make('user role')
                ->options([
                    'User' => 'User',
                    'Notary' => 'Notary',
                ]),

            Number::make('periodicity')->sortable()->min(1)->step(1)->hideFromIndex(),

            Select::make('periodicity type')
                ->options([
                    'Weekly' => 'Weekly',
                    'Monthly' => 'Monthly',
                    'Yearly' => 'Yearly',
                ])
                ->rules('required'),

            Number::make('amount')->sortable()->step(0.01),

            Select::make('discount applied')
                ->options([
                    true => 'true',
                    false => 'false',
                ])->rules('required')->hideFromIndex(),

            Select::make('trial')
                ->options([
                    true => 'true',
                    false => 'false',
                ])->rules('required')->hideFromIndex(),

            Select::make('next suggested plan')
                ->options([
                    'Pro' => 'Pro Plan',
                    'Business' => 'Business Plan',
                ])->rules('required')->hideFromIndex(),

            Text::make('discount mode')->sortable()->rules('required', 'max:255')->hideFromIndex(),

            Number::make('discount')->sortable()->step(0.01)->hideFromIndex(),

            Number::make('discount unit')->sortable()->min(0)->step(0.01)->hideFromIndex(),

            Number::make('discount percentage')->sortable()->min(0)->step(0.01)->hideFromIndex(),

            Number::make('discount amount')->sortable()->min(0)->step(0.01)->hideFromIndex(),

            Number::make('grace days')->sortable()->min(0)->step(1)->hideFromIndex(),

            Number::make('minimum discount unit')->sortable()->min(1)->step(1)->hideFromIndex(),

            Number::make('maximum discount unit')->sortable()->min(1)->step(1)->hideFromIndex(),

            Textarea::make('description')->sortable()->rules('required', 'max:255')->hideFromIndex(),

            HasMany::make('features'),

            MorphMany::make('transactions'),

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
}
