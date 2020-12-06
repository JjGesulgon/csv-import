<?php

namespace App;

use App\Traits\Filtering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes, Filtering;

    /**
     * Contacts table.
     *
     * @var string
     */
    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'team_id', 'name', 'phone', 'email', 'sticky_phone_number_id'
    ];

    /**
       * Run functions on boot.
       *
       * @return void
       */
    public static function boot()
    {
        parent::boot();
  
        static::creating(function ($model) {
            $model->user_id = auth('api')->user()->id;
        });
  
        static::updating(function ($model) {
            $model->user_id = auth('api')->user()->id;
        });
    }
  
    /**
     * The contact belongs to a user.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The contact has Many to a custom attributes.
     *
     * @return object
     */
    public function customAttributes()
    {
        return $this->hasMany(CustomAttributes::class);
    }
}
