{extends file="layouts/base.tpl"}
{block name="title"}会員一覧{/block}
{block name="body"}
    {include file='layouts/header.tpl'}
    <div class="container profile">
        <img src="{$avatarSrc}" alt="アバター" class="avatar">
        <h2>{$member.name}</h2>
        <p>メールアドレス: {$member.email}</p>
        <p>登録日: {$member.created_at}</p>
        <a href="/edit-profile/{$member.id}" class="btn edit-btn">プロフィール編集</a>
    </div>
{/block}
