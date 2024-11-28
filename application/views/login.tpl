{extends file="layouts/base.tpl"}
{block name="title"}ログイン{/block}
{block name="body"}
    <div class="container">
        <h1>ログイン</h1>
        <form onsubmit="onLoginSubmit(event);">
            <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" id="submit-button">ログイン</button>
        </form>
        <p>アカウントをお持ちでない方 <a href="/register">会員登録</a></p>
    </div>
{/block}
