<?php

namespace App;

use App\Traits\Filtering;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomAttributes extends Model
{
    use SoftDeletes, Filtering;

    /**
     * custom_attributes table.
     *
     * @var string
     */
    protected $table = 'custom_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'contact_id', 'key', 'value'
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
     * The customAttributes belongs to a user.
     *
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

     /**
     * The customAttributes belongs to a contact.
     *
     * @return object
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
