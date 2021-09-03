<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $guarded = [];

    public function subscribers()
    {
        return $this->belongsToMany(Subscriber::class);
    }

    /**
     * Create/Update a subscriber to current topic
     *
     * @param String $url URL to subscribe to topic
     *
     * @return void
     */
    public function addSubscriber(String $url)
    {
        $subscriber = Subscriber::updateOrCreate([ 'url' => $url ]);

        if($subscriber->wasRecentlyCreated){
            $this->subscribers()->attach($subscriber->id);
        }
    }
}
