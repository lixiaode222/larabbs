<nav class="navbar navbar-light navbar-expand-lg bg-light navbar-static-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="images/laravel.png" alt="laravel" style="margin-right: 15px;margin-top: -5px" width=35>
            Larabbs
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- 左边部分 --}}
            <ul class="navbar-nav mr-auto">

            </ul>

            {{-- 右边部分 --}}
            <ul class="navbar-nav navbar-right">
                <li class="nav-item"><a class="nav-link" href="#">登陆</a></li>
                <li class="nav-item"><a class="nav-link" href="#">注册</a></li>
            </ul>
        </div>
    </div>
</nav>