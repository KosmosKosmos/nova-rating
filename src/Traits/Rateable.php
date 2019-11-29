<?php namespace KosmosKosmos\Rating;

use KosmosKosmos\Rating\Models\Rating;

trait Rateable
{
    /**
     * This model has many ratings.
     *
     * @return Rating
     */
    public function ratings()
    {
        return $this->morphMany('KosmosKosmos\Rating\Models\Rating', 'rateable');
    }

    public function userRatings($category = NULL)
    {
        return $this->ratings()->fromCurrentUser()->withCategory($category);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function sumRating()
    {
        return $this->ratings()->sum('rating');
    }

    public function averageCategoryRatings($category)
    {
        return $this->ratings()->withCategory($category)->avg('rating');
    }

    public function userAverageRating()
    {
        return $this->ratings()->fromCurrentUser()->avg('rating');
    }

    public function userSumRating()
    {
        return $this->ratings()->fromCurrentUser()->sum('rating');
    }

    public function userHasRated($category = NULL)
    {
        return $this->userRatings($category)->count() > 0;
    }

    public function userDeleteRatings()
    {
        return $this->ratings()->fromCurrentUser()->delete();
    }

    public function ratingPercent($max = 5)
    {
        $quantity = $this->ratings()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->averageRating();
    }

    public function getSumRatingAttribute()
    {
        return $this->sumRating();
    }

    public function getUserAverageRatingAttribute()
    {
        return $this->userAverageRating();
    }

    public function getUserSumRatingAttribute()
    {
        return $this->userSumRating();
    }

    public function addRating($ratingValue, $category = NULL)
    {
        $rating = new Rating;
        $rating->rating = (int) $ratingValue;
        $rating->category = $category;
        $rating->user_id = \Auth::id();
        return $this->ratings()->save($rating);
    }

    public function updateRating($rating, $category)
    {
        if ($this->userHasRated($category)) {
            return $this->userRatings($category)->first()->update(["rating" => $rating]);
        } else {
            return $this->addRating($rating, $category);
        }
    }
}
