<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class PaymentGateway extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\PaymentGateway::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        return $this->paymentGatewayList?->name.' - '.$this->country?->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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

            BelongsTo::make('Payment Gateway List'),

            BelongsTo::make('Country')->searchable(),

            Boolean::make('Active')->trueValue('On')->falseValue('Off'),

            // Number::make('percentage gateway charge')->sortable()->step(0.01)->hideFromIndex(),

            // Number::make('percentage company charge')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Number::make('amount gateway chargee')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Number::make('amount company charge')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Number::make('amount gateway chargee')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Number::make('amount company charge')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Number::make('total')->sortable()->min(1)->step(0.01)->hideFromIndex(),

            // Select::make('apply percentage and amount')
            //     ->options([
            //         true => 'True',
            //         false => 'False',
            //     ])->hideFromIndex(),

            // Select::make('keys')
            //     ->options([
            //         true => 'True',
            //         false => 'False',
            //     ])->hideFromIndex(),

            // Select::make('discount applied')
            //     ->options([
            //         'percentage' => 'percentage',
            //         'amount' => 'amount',
            //     ])->hideFromIndex(),

            // Text::make('private test key')->rules( 'max:255')->hideFromIndex(),

            // Text::make('public test key')->rules('max:255')->hideFromIndex(),

            // Text::make('private live key')->rules('max:255')->hideFromIndex(),

            // Text::make('public live key')->rules('max:255')->hideFromIndex(),
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
