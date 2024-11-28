{extends file="layouts/base.tpl"}
{block name="title"}会員情報編集{/block}
{block name="body"}
    {include file="layouts/header.tpl"}
    <div class="container">
        <h1>会員情報編集</h1>
        <form id="edit-profile-form" action="/update-user" method="post" onsubmit="onRegisterSubmit(event, '/', false);">
            <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
            <input type="hidden" id="id" name="id" value="{$member.id}">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" value="{$member.name}" maxlength="100" required>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{$member.email}" maxlength="255" required>
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="32">
            <label for="avatar">アバター画像</label>
            <div>
                <img id="avatar-preview" class="avatar" src="/images/noimage_150x150.jpg" alt="アバターのプレビュー" style="margin-bottom: 10px;">
                <input type="file" id="avatar" name="avatar" accept="image/*">
            </div>
            <div class="action-buttons">
                <button type="submit" id="submit-button" class="btn">保存</button>
            </div>
        </form>
        <div class="back-link">
            {if $isProfile}
                <a href="/profile/{$member.id}" class="btn link-btn">プロフィールに戻る</a>
            {else}
                <a href="/" class="btn link-btn">ホームに戻る</a>
            {/if}
        </div>
    </div>
{/block}
