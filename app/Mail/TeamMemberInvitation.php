<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TeamMemberInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $detail;

    public function __construct($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verify_status = ! empty($this->detail?->user?->user_access_code) ? '0' : '1';

        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $this->detail?->user?->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        if ($verify_status == '0') {
            $link = config('externallinks.team_invite_url').'?email='.$this->detail?->user?->email.'&hash='.$token;
        }

        if ($verify_status == '1') {
            $link = config('externallinks.frontend_user_url');
        }

        return $this->to($this->detail->user->email)->subject('Collaboration is key!')->markdown('emails.team.TeamMemberInvitation')->with('link', $link);
    }
}
