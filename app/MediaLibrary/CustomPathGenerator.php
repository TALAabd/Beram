<?php

namespace App\MediaLibrary;

use App\Models\Banner;
use App\Models\BusinessSetting;
use Modules\Authentication\Models\User;
use Modules\Hotels\Models\Hotel;
use Modules\Hotels\Models\Room;
use Modules\Resturant\Models\Resturant;
use Modules\Resturant\Models\Table;
use Modules\Resturant\Models\Menu;
use App\Models\City;
use App\Models\Story;
use App\Models\StoryItem;
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
                return Hotel::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case User::class:
                return User::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Room::class:
                return Room::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Resturant::class:
                return Resturant::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Table::class:
                return Table::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Menu::class:
                return Menu::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Meal::class:
                return Meal::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case City::class:
                return City::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Banner::class:
                return Banner::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case Story::class:
                return Story::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case StoryItem::class:
                return StoryItem::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            case BusinessSetting::class:
                return BusinessSetting::PATH . DIRECTORY_SEPARATOR . $media->id . DIRECTORY_SEPARATOR;
                break;
            default:
                return $media->id . DIRECTORY_SEPARATOR;
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
