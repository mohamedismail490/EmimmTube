<?php /** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Channel extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function subscriptions() {
        return $this -> hasMany(Subscription::class);
    }

    public function image() {
        $media = $this -> media -> first();
        if (!empty($media)) {
            return $media -> getFullUrl('thumb');
        }

        return null;
    }

    public function editable() {
        return (auth() -> check()) && ($this -> user_id === auth() -> user() -> id);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this -> addMediaConversion('thumb')
//            -> height(100)
            ->fit('stretch', 100, 100);
//            -> width(100);
    }
}
