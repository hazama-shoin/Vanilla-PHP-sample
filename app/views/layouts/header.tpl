<header>
    <div class="header-container">
        <div class="menu-toggle">
            ☰
            <nav class="dropdown-menu">
                <div class="menu-profile">
                    <a href="/profile/{$user.id}">
                        <img src="/images/noimage.jpg" alt="アバター" class="menu-avatar">
                    </a>
                    <span class="menu-name">{$user.name}</span>
                </div>
                <ul>
                    <li><a href="/">ホーム</a></li>
                    <li><a href="/logout">ログアウト</a></li>
                </ul>
            </nav>
        </div>
        <a href="/" class="header-title">会員管理システム</a>
        <div class="search-icon" id="search-icon">
            <i class="fas fa-search"></i>
        </div>
    </div>
    <div class="search-bar" id="search-bar" style="display: none;">
        <input type="text" id="search-input" placeholder="会員を検索...">
        <button id="search-button">
            <i class="fas fa-search"></i>
        </button>
    </div>
</header>
