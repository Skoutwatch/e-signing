<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Feature extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Feature::class;

    public function title()
    {
        return $this->name.' -  Consumable - '.($this->consumable == 1 ? 'True' : 'False');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name',
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

            Text::make('description')->sortable()->rules('required', 'max:255')->hideFromIndex(),

            // Text::make('model type')->sortable()->rules('required', 'max:255')->hideFromIndex(),

            // Textarea::make('description')->sortable()->rules('required', 'max:255')->hideFromIndex(),

            // Select::make('consumable')
            //     ->options([
            //         true => 'true',
            //         false => 'false',
            //     ])->rules('required')->hideFromIndex(),

            // Select::make('quota')
            //     ->options([
            //         true => 'true',
            //         false => 'false',
            //     ])->rules('required')->hideFromIndex(),

            // Select::make('public')
            //     ->options([
            //         true => 'true',
            //         false => 'false',
            //     ])->rules('required'),

            // Number::make('periodicity')->sortable()->min(1)->step(1)->hideFromIndex(),

            // Select::make('periodicity type')
            //     ->options([
            //         'Weekly' => 'Weekly',
            //         'Monthly' => 'Monthly',
            //         'Yearly' => 'Yearly',
            //     ])
            //     ->rules('required')->hideFromIndex(),

            // BelongsToMany::make('plans'),
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
