<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Site extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = ['status_label'];

    protected $fillable = [
        'name',
        'status',
        'user_id',
        'url',
        'uuid',
        'remark',
        'upload_status'
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y年m月d日'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('Y年m月d日'),
        );
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->upload_status) {
            0 => '最適化作業前',
            1 => '最適化済み',
            2 => '登録エラー',
            3 => '取得エラー',
            default => '不明なエラー',
        };
    }
}
