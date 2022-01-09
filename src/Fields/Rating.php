<?php

namespace KosmosKosmos\Rating\Fields;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

class Rating extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'rating';

    /**
     * Rating constructor.
     *
     * @param string $name
     * @param null|string $attribute
     * @param mixed|null $resolveCallback
     */
    public function __construct(string $name, ?string $attribute = null, mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);
        $this->withMeta(['min' => 0, 'max' => 5]);
        $this->withStyles([]);
    }

    /**
     * Minimum rating.
     * default = 0.
     *
     * @param $min
     * @return Rating
     */
    public function min($min)
    {
        return $this->withMeta(['min' => $min]);
    }

    /**
     * @param $max int Maximum rating.
     * This will let the component know how many stars it should display.
     *
     * @return Rating
     */
    public function max($max)
    {
        return $this->withMeta(['max' => $max]);
    }

    /**
     * @param float The rating increment
     * for example pass 0.5 for half stars or 0.01 for fluid stars.
     * Expects a number between 0.01 - 1.
     *
     * @return Rating
     */
    public function increment($increment)
    {
        return $this->withMeta(['increment' => $increment]);
    }

    /**
     * Show rating value next to stars.
     * this is the default behaviour.
     *
     * @return Rating
     */
    public function showRating()
    {
        return $this->withMeta(['showRating' => true]);
    }

    /**
     * Hide rating value next to stars.
     * By default rating will be displayed unless you use this method.
     *
     * @return Rating
     */
    public function hideRating()
    {
        return $this->withMeta(['showRating' => false]);
    }

    /**
     * Style the component.
     *
     * @param array $styles
     * @return Rating
     */
    public function withStyles(array $styles)
    {
        $build = [];
        foreach (config('rating') as $key => $defaultValue) {
            $build[$key] = array_get($styles, $key, $defaultValue);
        }

        return $this->withMeta($build);
    }

    public function resolve($resource, $attribute = null) : void
    {
        parent::resolve($resource, $attribute);

        $category = $attribute == 'averageRating' ? NULL : $this->attribute;

        $this->withMeta([
            'value' => $resource->{$this->attribute},
            'resource' => get_class($resource),
            'resource_id' => $resource->id,
            'category' => $category,
            'endpoint' => '/nova-vendor/kosmoskosmos/setrating'
        ]);
    }

    public function starSize($size) {
        $this->withMeta(['star-size' => $size]);
    }

    public function starPadding($padding) {
        $this->withMeta(['padding' => $padding]);
    }
}
