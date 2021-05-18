<?php namespace KosmosKosmos\Rating\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use KosmosKosmos\Rating\Models\Rating;

trait Rateable {
    /**
     * This model has many ratings.
     *
     * @return Rating
     */
    public function ratings() {
        return $this->morphMany('KosmosKosmos\Rating\Models\Rating', 'rateable');
    }

    public function userRatings($category = null) {
        return $this->ratings()->fromCurrentUser()->withCategory($category);
    }

    public function averageRating() {
        return $this->ratings()->avg('rating');
    }

    public function sumRating() {
        return $this->ratings()->sum('rating');
    }

    public function averageCategoryRatings($category) {
        return $this->ratings()->withCategory($category)->avg('rating');
    }

    public function sumCategoryRatings($category) {
        return $this->ratings()->withCategory($category)->sum("rating");
    }

    public function averageUserRating() {
        return $this->ratings()->fromCurrentUser()->avg('rating');
    }

    public function userSumRating() {
        return $this->ratings()->fromCurrentUser()->sum('rating');
    }

    public function userHasRated($category = null) {
        return $this->userRatings($category)->count() > 0;
    }

    public function userDeleteRatings() {
        return $this->ratings()->fromCurrentUser()->delete();
    }

    public function ratingPercent($max = 5) {
        $quantity = $this->ratings()->count();
        $total = $this->sumRating();

        return ($quantity * $max) > 0 ? $total / (($quantity * $max) / 100) : 0;
    }

    /**
     * This function resolves rating attributes.
     * - It shall be called in the getAttribute($key) method of the Model that implements "Rateable".
     * - All attribute names ending on _rating or Rating are considered to be fields that are used by this trait.
     * - If the attribute name ends on _average_rating, AverageRating _sum_rating or SumRating we try to resolve averages or sums across all users
     * - If the attribute name does not use _average_, Average, _sum_ or Sum we try to retrieve the user-generated rating or return NULL if a rating was not found
     *
     * @param $key
     *
     * @return string|null
     */
    public function resolveRatingAttribute($key) : ?string {

        if ( ! Str::endsWith($key, ["_rating","Rating"])) {
            return null;
        }

        if ( ! Str::contains($key, ['average', 'sum'])) {
            $value = $this->userRatings($key)->first();
            return $value ? $value->rating : null;
        }

        $ratingMethod = Str::camel($key);
        if (method_exists($this, $ratingMethod)) {
            return $this->$ratingMethod();
        }

        $ratingCompositionMethod  = (Str::contains($key, "average")) ? "average" : "sum";

        $ratingKeysPossible = [
            "snake" => "_".$ratingCompositionMethod."_rating",
            "camel" => ucfirst($ratingCompositionMethod)."Rating"
        ];

        foreach ($ratingKeysPossible as $case => $possibleRatingKey) {
            if (Str::endsWith($key, $possibleRatingKey)) {
                $methodName = $ratingCompositionMethod."CategoryRatings";
                $ratingKeyName = Str::before($key, $possibleRatingKey);
                $ratingKey = Str::$case($ratingKeyName."_rating");
                return $this->$methodName($ratingKey);
            }
        }

        return null;
    }

    public function getAverageRatingAttribute() {
        return $this->averageRating();
    }

    public function getSumRatingAttribute() {
        return $this->sumRating();
    }

    public function getAverageUserRatingAttribute() {
        return $this->userAverageRating();
    }

    public function getSumUserRatingAttribute() {
        return $this->userSumRating();
    }

    public function getUserHasRatedAttribute() {
        return $this->userHasRated();
    }

    public function addRating($ratingValue, $category = null) {
        $rating = new Rating;
        $rating->rating = (float) $ratingValue;
        $rating->category = $category;
        $rating->user_id = \Auth::id();

        return $this->ratings()->save($rating);
    }

    public function updateRating($rating, $category) {
        if ($this->userHasRated($category)) {
            return $this->userRatings($category)->first()->update(["rating" => $rating]);
        }
        else {
            return $this->addRating($rating, $category);
        }
    }
}
