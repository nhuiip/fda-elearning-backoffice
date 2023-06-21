<?php

namespace App\Http\Controllers;

use App\Mail\MemberInfoMail;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendMail($memberId)
    {
        $member = Member::find($memberId);
        $this->updateNotified($member->id);
        return Mail::to($member->email)->send(new MemberInfoMail($member));
    }
    public function sendMailAll()
    {
        $member = Member::all();
        foreach ($member as $data) {
            try {
                $this->updateNotified($data->id);
                Mail::to($data->email)->send(new MemberInfoMail($data));
            } catch (\Exception $e) {
            }
        }
        return count($member);;
    }
    public function sendMailNewMember()
    {
        $member = Member::where('notified', false)->get();
        foreach ($member as $data) {
            try {
                $this->updateNotified($data->id);
                Mail::to($data->email)->send(new MemberInfoMail($data));
            } catch (\Exception $e) {
            }
        }
        return count($member);
    }
    private function updateNotified($memberId)
    {
        $data = Member::findOrFail($memberId);
        if ($data != null) {
            $data->notified = true;
            $data->save();
        }
    }
}
