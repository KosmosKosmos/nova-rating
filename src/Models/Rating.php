<?php namespace KosmosKosmos\Rating\Models;

use Config;
use KosmosKosmos\Rating\Events\RatingUpdatedEvent;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = "ratings";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = ['rating'];

    protected static function boot() {
        static::saved(function (Rating $rating) {
            event(new RatingUpdatedEvent($rating));
        });
        parent::boot();
    }

    /**
     * @return mixed
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * Rating belongs to a user.
     *
     * @return User
     */
    public function user()
    {
        $userClassName = Config::get('auth.model');
        if (is_null($userClassName)) {
            $userClassName = Config::get('auth.providers.users.model');
        }

        return $this->belongsTo($userClassName);
    }

    public function scopeFromCurrentUser($query) {
        return $query->where("user_id",\Auth::id());
    }


    public function scopeWithCategory($query, $category) {
        return $query->where("category",$category);
    }
}
