{extends file="layouts/base.tpl"}
{block name="title"}会員登録{/block}
{block name="body"}
    {if isset($user)}
        {include file='layouts/header.tpl'}
    {/if}
    <div class="container">
        <h1>会員登録</h1>
        <form id="register-form" enctype="multipart/form-data" onsubmit="onRegisterSubmit(event, '/');">
            <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" maxlength="100" required>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" maxlength="255" required>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="32" required>
            {if isset($user) && $user.is_admin}
                <label for="is_admin">
                    <input type="checkbox" id="is_admin" name="is_admin">
                    管理者に設定する
                </label>
            {/if}
            <label for="avatar">アバター画像</label>
            <div>
                <img id="avatar-preview" class="avatar" src="/images/noimage_150x150.jpg" alt="アバターのプレビュー" style="margin-bottom: 10px;">
                <input type="file" id="avatar" name="avatar" accept=".jpg, .jpeg, .png">
            </div>
            <button type="submit" id="submit-button" class="btn">登録</button>
        </form>
        <p>
            {if isset($user)}
                <a href="/">ホームに戻る</a></p>
            {else}
                既にアカウントをお持ちの方 <a href="/">ログイン</a>
            {/if}
        </p>
    </div>
{/block}
