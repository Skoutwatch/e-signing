<?php

namespace App\Nova;

use App\Nova\Metrics\Users\NewUsers;
use App\Nova\Metrics\Users\UserCount;
use App\Nova\Metrics\Users\UsersPerDay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Notary extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    public function title()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public static $search = [
        'id',
        'first_name',
        'last_name',
        'email',
        'gender',
        'bvn',
        'nin',
        'drivers_license_no',
        'notary_commission_number',
        'address',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable()->hideFromIndex(),

            Gravatar::make()->maxWidth(50),

            Text::make('first name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('last name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('phone')->hideWhenCreating()->hideWhenUpdating(),

            Text::make('Email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults())
                ->hideWhenUpdating(),

            Select::make('Role')->options([
                'Admin' => 'Admin',
                'Notary' => 'Notary',
            ])->displayUsingLabels(),

            Boolean::make('National Verification')->hideWhenCreating()->hideWhenUpdating(),

            Boolean::make('System Verification')->hideWhenCreating()->hideWhenUpdating(),

            BelongsTo::make('Country')->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),

            BelongsTo::make('State')->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),

            BelongsTo::make('City')->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),

            Text::make('User Access Code')->hideFromIndex()->hideWhenCreating()->hideWhenUpdating(),

            // HasMany::make('Document Uploads'),

            // HasOne::make('Team', 'team'),

            // HasMany::make('Company', 'company'),

            // HasMany::make('Transactions', 'transactions'),

            // HasMany::make('User Payment Gateway', 'userPaymentGateway'),

            // HasMany::make('Schedule Session', 'userScheduledSessions'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
            new UserCount,
            new NewUsers,
            new UsersPerDay,
        ];
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
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role', '=', 'Notary');
    }
}
