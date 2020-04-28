<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{

    protected $fillable = [
        'article_id',
        'editor_id',
        'tags',
        'old_tags',
        'header',
        'description',
        'text',
        'publish',
    ];
    protected static function boot(){
        parent::boot();
        static::creating(function ($version){
            if ($version->type === Models\Article::class){
                unset ($version->type);
            }
            if ($version->type === News::class){
                dd($version->toArray());
                VersionNews::create($version->toArray());
            }
        });
            
        
    }

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
        return $this->article()->where('id', $this->article_id)->first();
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

   
}
