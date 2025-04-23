<?php

namespace App\Models;

use App\Models\Default\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Journal extends Model
{
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'transaction_date',
        'description',
        'model',
        'model_id',
    ];

    protected $appends = ['type_text'];

    protected static function booted(): void
    {
        static::creating(function (Journal $model) {
            if ($model->type == self::TYPE_IN) {
                $model->account->update([
                    'balance_amount' => $model->account->balance_amount + $model->amount,
                ]);
            }
            if ($model->type == self::TYPE_OUT) {
                $model->account->update([
                    'balance_amount' => $model->account->balance_amount - $model->amount,
                ]);
            }
        });

        static::deleting(function (Journal $model) {
            if ($model->type == self::TYPE_IN) {
                $model->account->update([
                    'balance_amount' => $model->account->balance_amount - $model->amount,
                ]);
            }
            if ($model->type == self::TYPE_OUT) {
                $model->account->update([
                    'balance_amount' => $model->account->balance_amount + $model->amount,
                ]);
            }
        });
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function typeText(): Attribute
    {
        return Attribute::make(get: fn() => $this->type == self::TYPE_IN ? 'Pemasukan' : 'Pengeluaran');
    }
}
