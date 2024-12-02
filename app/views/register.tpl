{extends file="layouts/base.tpl"}
{block name="title"}会員登録{/block}
{block name="body"}
    {if $isLogined}
        {include file='layouts/header.tpl'}
    {/if}
    <div class="container">
        <h1>会員登録</h1>
        <form id="register-form" enctype="multipart/form-data" action="/register" method="post" onsubmit="onRegisterSubmit(event, '/');">
            <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" maxlength="100" required>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" maxlength="255" required>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="32" required>
            <label for="avatar">アバター画像</label>
            <div>
                <img id="avatar-preview" class="avatar" src="/images/noimage_150x150.jpg" alt="アバターのプレビュー" style="margin-bottom: 10px;">
                <input type="file" id="avatar" name="avatar" accept="image/*">
            </div>
            <button type="submit" id="submit-button" class="btn">登録</button>
        </form>
        <p>
            {if $isLogined}
                <a href="/">ホームに戻る</a></p>
            {else}
                既にアカウントをお持ちの方 <a href="/">ログイン</a>
            {/if}
        </p>
    </div>
{/block}
