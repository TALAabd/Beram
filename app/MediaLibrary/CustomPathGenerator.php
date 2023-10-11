<?php

namespace App\MediaLibrary;

use App\Models\About;
use App\Models\Banner;
use App\Models\BusinessSetting;
use Modules\Authentication\Models\User;
use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Models\Room;
use Modules\Resturant\Models\Resturant;
use Modules\Resturant\Models\Table;
use Modules\Resturant\Models\Menu;
use App\Models\City;
use App\Models\PaymentMethod;
use App\Models\Story;
use App\Models\StoryItem;
use App\Models\Trip;
use Modules\Booking\Models\Booking;
use Modules\Resturant\Models\Meal;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class CustomPathGenerator
 * @package App\MediaLibrary
 */
class CustomPathGenerator implements PathGenerator
{
    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPath(Media $media): string
    {
        switch ($media->model_type) {
            case Hotel::class:
                return Hotel::PATH . '/' . $media->id . '/';
                break;
            case User::class:
                return User::PATH . '/' . $media->id . '/';
                break;
            case Room::class:
                return Room::PATH . '/' . $media->id . '/';
                break;
            case Resturant::class:
                return Resturant::PATH . '/' . $media->id . '/';
                break;
            case Table::class:
                return Table::PATH . '/' . $media->id . '/';
                break;
            case Menu::class:
                return Menu::PATH . '/' . $media->id . '/';
                break;
            case Meal::class:
                return Meal::PATH . '/' . $media->id . '/';
                break;
            case City::class:
                return City::PATH . '/' . $media->id . '/';
                break;
            case Banner::class:
                return Banner::PATH . '/' . $media->id . '/';
                break;
            case Story::class:
                return Story::PATH . '/' . $media->id . '/';
                break;
            case StoryItem::class:
                return StoryItem::PATH . '/' . $media->id . '/';
                break;
            case BusinessSetting::class:
                return BusinessSetting::PATH . '/' . $media->id . '/';
                break;
            case Trip::class:
                return Trip::PATH . '/' . $media->id . '/';
                break;
            case About::class:
                return About::PATH . '/' . $media->id . '/';
                break;
            case PaymentMethod::class:
                return PaymentMethod::PATH . '/' . $media->id . '/';
                break;
            case Booking::class:
                return Booking::PATH . '/' . $media->id . '/';
                break;
            default:
                return $media->id . '/';
        }
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'thumbnails/';
    }

    /**
     * @param Media $media
     *
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'rs-images/';
    }
}
