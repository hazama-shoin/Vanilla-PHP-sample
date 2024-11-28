{extends file="layouts/base.tpl"}
{block name="title"}ログイン{/block}
{block name="body"}
    <div class="container">
        <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
        <div id="login-form">
            <h1>ログイン</h1>
            <form onsubmit="onLoginSubmit(event);">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" id="login_submit_button">ログイン</button>
            </form>
            <p>アカウントをお持ちでない方 <a href="javascript:void(0)" onclick="toggleFormVisibility();">会員登録</a></p>
        </div>
        <div id="regist-form" class="hidden">
            <h1>新規会員登録</h1>
            <form onsubmit="onSendMailSubmit(event);">
                <label for="name">名前</label>
                <input type="text" id="name" name="name" maxlength="100" required>
                <label for="register_email">メールアドレス</label>
                <input type="email" id="register_email" name="email" maxlength="255" required>
                <button type="submit" id="regist_submit_button">送信</button>
            </form>
            <p>既にアカウントをお持ちの方 <a href="javascript:void(0)" onclick="toggleFormVisibility();">ログイン</a></a></p>
        </div>
    </div>
{/block}
{block name="add-script"}
    <script src="/js/login.js"></script>
{/block}
