<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\Subscription\SubscriptionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->user?->team?->id != $this->user?->activeTeam?->team?->id ? 'My Team' : ($this->user->first_name.' '.$this->user->last_name).'\'s Team',
            'active' => $this->user?->activeTeam?->team_id == $this->id,
            'image' => $this->user?->company ? $this->user?->company?->logo : $this->user?->image,
            'notify_owner_when_document_complete' => $this->notify_owner_when_document_complete ? true : false,
            'notify_owner_when_a_signer_refuse_to_sign' => $this->notify_owner_when_a_signer_refuse_to_sign ? true : false,
            'notify_owner_when_each_signer_views_a_document' => $this->notify_owner_when_each_signer_views_a_document ? true : false,
            'notify_owner_always_cc_admin' => $this->notify_owner_always_cc_admin ? true : false,
            'notify_signer_when_to_sign_a_document' => $this->notify_signer_when_to_sign_a_document ? true : false,
            'notify_signer_when_document_complete' => $this->notify_signer_when_document_complete ? true : false,
            'notify_signer_when_signer_declines_to_sign_document' => $this->notify_signer_when_signer_declines_to_sign_document ? true : false,
            'notify_signer_when_owner_withdraws_document' => $this->notify_signer_when_owner_withdraws_document ? true : false,
            'notify_signer_always_cc_admin' => $this->notify_signer_always_cc_admin ? true : false,
            'notify_signer_when_document_updated' => $this->notify_signer_when_document_updated ? true : false,
            'notify_display_transaction_to_team' => $this->notify_display_transaction_to_team ? true : false,
            'notify_display_transaction_as_mine' => $this->notify_display_transaction_as_mine ? true : false,
            'send_sms' => $this->send_sms ? true : false,
            'send_email' => $this->send_email ? true : false,
            'subscription' => new SubscriptionResource($this->subscription),
            'users' => TeamUserShortDetailsResource::collection($this->whenLoaded('users')),
        ];
    }
}
