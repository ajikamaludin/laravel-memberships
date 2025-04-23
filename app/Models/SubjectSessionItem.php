<?php

namespace App\Models;

use App\Models\Default\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class SubjectSessionItem extends Model
{
    protected $fillable = [
        'session_id',
        'member_id',
        'membership_id',
        'subject_id',
        'session_date',
        'flag',
    ];

    protected $appends = [
        'time_text',
    ];

    public function subjectSession()
    {
        return $this->belongsTo(SubjectSession::class, 'session_id')->withTrashed();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class)->withTrashed();
    }

    public function member()
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class)->withTrashed();
    }

    public function timeText(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->subjectSession != null) {
                    return $this->subjectSession->trainingTime->name;
                }

                return Carbon::parse($this->created_at)->format('H:i');
            },
        );
    }
}
