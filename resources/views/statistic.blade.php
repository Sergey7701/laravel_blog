@include ('layouts.header', ['title' => 'Статистика сайта'])
<h3>Статистика сайта:</h3>
<p class="col-12">
    Всего опубликованных записей: {{ $countPublishedEntries }}
</p>
<p class="col-12 mr-1">
    - из них статей: {{ $countPublishedArticles }}
</p>
<p class="col-12 mr-1">
    - из них новостей: {{ $countPublishedNews }}
</p>
<p class="col-12">
    Самый длинный текст у статьи: {{ $maxLengthTextOfArticle }}
</p>
<p class="col-12">
    Самый длинный текст у новости: {{ $maxLengthTextOfNews }} 
</p>
<p class="col-12">
    Самый короткий текст у статьи: {{ $minLengthTextOfArticle }}
</p>    
<p class="col-12">
    Самый короткий текст у новости: {{ $minLengthTextOfNews }} 
</p>
<p class="col-12">
    Всего зарегистрированных пользователей: {{ $countUsers }}
</p>
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись (Entry): {{ $mostUsersCount }}
</p>
<p class="col-12 mr-1">
    - из них опубликовали хотя бы одну запись: {{ $mostUserWithoutEntryCount}}
</p>
<p class="col-12 ">
    Самый пишущий автор (Entry): {{ $mostUser }}
</p>
<p class="col-12 ">
    Самый пишущий автор: {{ $mostUserWithoutEntry }}
</p>
<p class="col-12 mr-1">
    - у него записей (Entry): {{ $mostUserResult }}
</p>
<p class="col-12 mr-1">
    - у него записей: {{ $mostUserResultWithoutEntry }}
</p>
<p class="col-12 ">
    Всего комментариев: {{ $countComments }}
</p>
<?php
$prefix = ($entry->entryable_type === 'App\News') ? 'news' : 'posts';

?>
<p class="col-12 ">
    Самая комментируемая запись: <a href="{{ '/'.$prefix.'/'.$entry->entryable->slug }}">{{ $entry->entryable->header }}</a>
</p>
<p class="col-12 mr-1">
    - у неё комментариев: {{ $entry->comments->count() }}</a>
</p>
<p class="col-12 ">
    Самый активный комментатор, без черновиков: {{ $mostCommentator }}
</p>
<p class="col-12 mr-1">
    - у него комментариев: {{ $mostCommentatorCount }}
</p>
<p class="col-12 ">
    Самый активный комментатор, включая черновики: {{ $mostCommentatorWithDraftes }}
</p>
<p class="col-12 mr-1">
    - у него комментариев: {{ $mostCommentatorWithDraftesCount }}
</p>
<?php
if ($mostEditingArticle) {

    ?>
    <p class="col-12">
        Самая часто меняемая статья: <a href="/posts/{{ $mostEditingArticle->slug }}">{{ $mostEditingArticle->header}}</a>
    </p>
    <?php
} else {

    ?>
    <p class="col-12">
        Самая часто меняемая статья: пока статьи не редактировались
    </p>
    <?php
}

?>
<?php
if ($mostEditingNews) {

    ?>
    <p class="col-12">
        Самая часто меняемая новость: <a href="/news/{{ $mostEditingNews->slug }}">{{ $mostEditingNews->header}}</a>
    </p>
    <?php
} else {

    ?>
    <p class="col-12">
        Самая часто меняемая новость: пока новости не редактировались
    </p>
    <?php
}

?>
<p class="col-12">
    Тегов на сайте: {{ $tagsCount }}
</p>  
<p class="col-12 mr-1">
    - из них используются в опубликованных записях и черновиках: {{ $usedTagsCount }}
</p>  
<p class="col-12 mr-1">
    - из них используются только в опубликованных записях: {{ $usedTagsPublishedCount  }}
</p>  
@include ('layouts.footer')