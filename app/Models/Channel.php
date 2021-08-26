<?php /** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Channel extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = ['channel_image','is_subscribed','is_owner','subscriptions_count','user_subscription'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function subscriptions() {
        return $this -> hasMany(Subscription::class);
    }
    public function videos() {
        return $this -> hasMany(Video::class)->latest('created_at');
    }

    public function getIsSubscribedAttribute() {
        if (auth()->check()) {
            $subscription = $this->subscriptions()->where('user_id', auth()->user()->id)->first();
            if (!empty($subscription)){
                return true;
            }
        }
        return false;
    }
    public function getIsOwnerAttribute() {
        return auth()->check() ? ($this->user_id === auth()->user()->id) : false;
    }
    public function getSubscriptionsCountAttribute(){
        return $this->subscriptions()->count();
    }
    public function getUserSubscriptionAttribute() {
        if (auth()->check()) {
            $userSubscription = $this->subscriptions()->where('user_id', auth()->user()->id)->first();
            if (!empty($userSubscription)){
                return (object) $userSubscription;
            }
        }
        return false;
    }
    public function getChannelImageAttribute() {
        return $this->image();
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
        $this->addMediaConversion('thumb')
            ->fit('stretch', 100, 100)
            ->nonQueued();
    }
}
