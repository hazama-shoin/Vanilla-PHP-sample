{extends file="layouts/base.tpl"}
{block name="title"}会員情報編集{/block}
{block name="body"}
    {include file="layouts/header.tpl"}
    <div class="container">
        <h1>会員情報編集</h1>
        <form id="edit-profile-form" onsubmit="onUpdateSubmit(event, '/');">
            <input type="hidden" id="csrf_token" name="csrf_token" value="{$csrfToken}">
            <input type="hidden" id="id" name="id" value="{$member.id}">
            <label for="name">名前</label>
            <input type="text" id="name" name="name" value="{$member.name}" maxlength="100" required>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" value="{$member.email}"
                {if $user.is_admin && $user.id !== $member.id}
                    maxlength="255" required
                {else}
                    readonly
                {/if}
            >
            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="32">
            {if $user.is_admin && $user.id !== $member.id}
                <label for="is_admin">
                    <input type="checkbox" id="is_admin" name="is_admin" {if $member.is_admin}checked{/if}>
                    管理者に設定する
                </label>
            {/if}
            <label for="avatar">アバター画像</label>
            <div>
                <img id="avatar-preview" class="avatar" src="{$avatarSrc}" alt="アバターのプレビュー" style="margin-bottom: 10px;">
                <input type="file" id="avatar" name="avatar" accept=".jpg, .jpeg, .png">
            </div>
            <div class="action-buttons">
                <button type="submit" id="submit_button" class="btn">保存</button>
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
{block name="add-script"}
    <script src="/js/upsert_user_common.js"></script>
    <script src="/js/edit_user.js"></script>
{/block}
