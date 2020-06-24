<?php
namespace App\Traits;

use App\Tag;

trait FlushCacheIfNeeded
{

    protected function flushCacheIfTagsChanged($article, $oldTags, $newTags)
    {
        \App\News::flushQueryCache(['version_' . $article->getUrlPrefix() . '-for-' . get_class($article) . '-' . $article->id . '_']);
        if ($newTags !== $oldTags) {
            get_class($article)::flushQueryCache(['tags-for-' . get_class($article) . '-' . $article->id . '_']);
            get_class($article)::flushQueryCache(['version_' . $article->getUrlPrefix() . '-for-' . get_class($article) . '-' . $article->id . '_']);
            Tag::flushQueryCache();
        }
    }
}
