<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Mail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'from',
        'to',
        'subject',
        'text_content',
        'html_content',
        'status',
        'attachments',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     * @return array
     */
    protected $casts = [
        'attachments' => 'array',
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $appends = ['statuses'];

    /**
     * The possible statuses of a mail.
     */
    public const STATUS_POSTED = 'Posted';
    public const STATUS_SENT = 'Sent';
    public const STATUS_FAILED = 'Failed';

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get count of mail with posted status
     */
    public static function getPostedCount()
    {
        return self::where('status', self::STATUS_POSTED)->count();
    }

    /**
     * Get count of mail with sent status
     */
    public static function getSentCount()
    {
        return self::where('status', self::STATUS_SENT)->count();
    }

    /**
     * Get count of mail with failed status
     */

    public static function getFailedCount()
    {
        return self::where('status', self::STATUS_FAILED)->count();
    }

    public function statuses(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Status::class, 'statusable');
    }

    /**
     * The booted function.
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();;
        });
    }
}
