<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $fillable = [
        'article_id',
        'editor_id',
        'tags',
        'header',
        'description',
        'text',
        'publish',
    ];

    public function article()
    {
        return $this->belongsTo(\App\Models\Article::class);
    }

    public function editor()
    {
        return User::findOrFail($this->editor_id)->name;
    }

    public function scopeRecentArticle()
    {
        return $this->article()->latest()->first();
    }

    public function setHeaderAttribute($newValue)
    {
        $this->attributes['header']     = $newValue;
        $this->attributes['old_header'] = $this->recentArticle()->header;
    }

    public function setDescriptionAttribute($newValue)
    {
        $this->attributes['description']     = $newValue;
        $this->attributes['old_description'] = $this->recentArticle()->description;
    }

    public function setTextAttribute($newValue)
    {
        $this->attributes['text']     = $newValue;
        $this->attributes['old_text'] = $this->recentArticle()->text;
    }

    public function setPublishAttribute($newValue)
    {
        $this->attributes['publish']     = $newValue ? 1 : 0;
        $this->attributes['old_publish'] = $this->recentArticle()->publish;
    }

    public function setTagsAttribute($newValue)
    {
        $this->attributes['old_tags'] = $newValue;
        $this->attributes['tags']     = $this->recentArticle()->tags->implode('name', ', ');
    }
}
