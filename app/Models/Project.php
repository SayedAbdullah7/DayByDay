<?php

namespace App\Models;

use App\Observers\ElasticSearchObserver;
use App\Traits\DeadlineTrait;
use App\Traits\SearchableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\Comment\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Carbon\Carbon;

class Project extends model implements Commentable
{
    use  SoftDeletes, SearchableTrait, DeadlineTrait;
    const PROJECT_STATUS_CLOSED = "Closed";

    protected $searchableFields = ['title'];

    protected $fillable = [
        'title',
        'description',
        'external_id',
        'status_id',
        'user_assigned_id',
        'user_created_id',
        'client_id',
        'deadline',
        'unit_type',
        'sub_type',
        'bedroom',
        'bathroom',
        'dressing_room',
        'area',
        'bua',
        'land_area',
        'garage',
        'roof_area',
        'floor_number',
        'apartment_number',
        'elevator',
        'view',
        'finished_status',
        'furniture_status',
        'comment',
        'payment_method',
        'installments_policy',
        'period',
        'price',
    ];

    protected $dates = ['deadline'];

    public static function boot()
    {
        parent::boot();

        // This makes it easy to toggle the search feature flag
        // on and off. This is going to prove useful later on
        // when deploy the new search engine to a live app.
        //if (config('services.search.enabled')) {
        static::observe(ElasticSearchObserver::class);
        //}
    }

    public function getRouteKeyName()
    {
        return 'external_id';
    }

    public function displayValue()
    {
        return $this->title;
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_created_id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_assigned_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_assigned_id');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'source');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'source');
    }

    public function isClosed()
    {
        return $this->status->title == self::PROJECT_STATUS_CLOSED;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'source');
    }

    public static function whereExternalId($external_id)
    {
        return self::where('external_id', $external_id)->first();
    }

    public function getCreateCommentEndpoint(): String
    {
        return route('comments.create', ['type' => 'project', 'external_id' => $this->external_id]);
    }

    /**
     * @return array
     */
    public function getSearchableFields(): array
    {
        return $this->searchableFields;
    }
}
