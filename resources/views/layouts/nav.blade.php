@permission('manage-articles')
<div id="app">
    <float-notify-component></float-notify-component>
</div>
@endpermission    
    <header class="blog-header py-3 container">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <a class="text-muted" href="#">Subscribe</a>
            </div>
            <div class="col-4 text-center">
                <a class="blog-header-logo text-dark" href="#">{{ config('app.name', 'No name')}}</a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <a class="text-muted" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-3"><circle cx="10.5" cy="10.5" r="7.5"></circle><line x1="21" y1="21" x2="15.8" y2="15.8"></line></svg>
                </a>
                @if (Route::has('login'))                        
                <div class="top-right links">
                    @auth
                    <script>
                        var userId = {{ Auth::id() }}
                    </script>
                    <a id="user_link" href="{{url('/home')}}">{{Auth::user()->name}}</a>
                    <form id="logout" method="POST" action="/logout">
                        {{ csrf_field() }}
                        <input type="hidden" name="page" value="/logout">
                            <input type="submit" class="btn-info p-1" name="ok" value="Logout">
                                </form>
                                @else
                                  <script>
                                      var userId = undefined;
                                  </script>
                                  <a href="{{url('/login')}}" style="margin-right: 10px">Login</a>
                                @if (Route::has('register'))
                                <a href="{{route('register')}}">Register</a>
                                @endif
                                @endauth
                                </div>
                                @endif

                                <!-- <a class="btn btn-sm btn-outline-secondary" href="#">Sign up</a> -->
                                </div>
                                </div>
                                </header>
                                @php
                                if(session('admin') === true) {
                                @endphp    
                                <div class="nav-scroller py-1 mb-2 container">
                                    <nav class="nav d-flex justify-content-between">
                                        <a class="p-2 text-muted" href="/admin/posts">Главная для администраторов</a>
                                        <a class="p-2 text-muted" href="/admin/news/create">Новая новость</a>
                                        <a class="p-2 text-muted" href="/posts">Пользовательский раздел</a>
                                        <a class="p-2 text-muted" href="/admin/feedbacks">Список обращений</a>
                                        <a class="p-2 text-muted" href="/admin/report">Запросить статистику</a>
                                    </nav>
                                </div>
                                @php    
                                } else {
                                @endphp    
                                <div class="nav-scroller py-1 mb-2 container">
                                    <nav class="nav d-flex justify-content-between">
                                        <a class="p-2 text-muted" href="/posts">Главная</a>
                                        <a class="p-2 text-muted" href="/about">О нас</a>
                                        <a class="p-2 text-muted" href="/contacts">Контакты</a>
                                        <a class="p-2 text-muted" href="/posts/create">Новая статья</a>
                                        <a class="p-2 text-muted" href="/statistic">Статистика сайта</a>
                                        @permission('manage-articles')
                                        <a class="p-2 text-muted" href="/admin/">Административный раздел</a>
                                        @endpermission
                                    </nav>
                                </div>
                                @php
                                }
                                @endphp
                                <!--
                                            <div class="jumbotron p-3 p-md-5 text-white rounded bg-dark">
                                                <div class="col-md-6 px-0">
                                                    <h1 class="display-4 font-italic">Title of a longer featured blog post</h1>
                                                    <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and efficiently about what's most interesting in this post's contents.</p>
                                                    <p class="lead mb-0"><a href="#" class="text-white font-weight-bold">Continue reading...</a></p>
                                                </div>
                                            </div>
                                
                                            <div class="row mb-2">
                                                <div class="col-md-6">
                                                    <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                                                        <div class="card-body d-flex flex-column align-items-start">
                                                            <strong class="d-inline-block mb-2 text-primary">World</strong>
                                                            <h3 class="mb-0">
                                                                <a class="text-dark" href="#">Featured post</a>
                                                            </h3>
                                                            <div class="mb-1 text-muted">Nov 12</div>
                                                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                                                            <a href="#">Continue reading</a>
                                                        </div>
                                                        <img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Card image cap">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card flex-md-row mb-4 shadow-sm h-md-250">
                                                        <div class="card-body d-flex flex-column align-items-start">
                                                            <strong class="d-inline-block mb-2 text-success">Design</strong>
                                                            <h3 class="mb-0">
                                                                <a class="text-dark" href="#">Post title</a>
                                                            </h3>
                                                            <div class="mb-1 text-muted">Nov 11</div>
                                                            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
                                                            <a href="#">Continue reading</a>
                                                        </div>
                                                        <img class="card-img-right flex-auto d-none d-lg-block" data-src="holder.js/200x250?theme=thumb" alt="Card image cap">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                -->