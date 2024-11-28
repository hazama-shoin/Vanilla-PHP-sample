{extends file="layouts/base.tpl"}
{block name="title"}会員一覧{/block}
{block name="body"}
    {include file='layouts/header.tpl'}
    <div class="container">
        <div class="action-buttons">
          <a href="/register" class="btn">会員追加</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>アバター</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$members item=member}
                    <tr>
                        <td>{$member.id}</td>
                        <td class="table-avatar">
                            <a href="/profile/{$member.id}">
                                <img src="/images/noimage.jpg" alt="アバター">
                            </a>
                        </td>
                        <td>
                            <a href="/profile/{$member.id}">{$member.name}</a>
                        </td>
                        <td>{$member.email}</td>
                        <td>
                            <a href="/edit-user/{$member.id}" class="btn edit-btn">編集</a>
                            <a href="#" class="btn delete-btn" onclick="onDeleteUserSubmit({$member.id}, {$user.id})">削除</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
        <div class="pagination" id="pagination-container">
            {if $currentPage > 1}
                <button id="prev-page" class="arrow-btn" onclick="location.href='/pages/{$currentPage - 1}'"><</button>
            {/if}
            {for $i=1 to $maxPages}
                {if $i === $currentPage}
                    <button class="active">{$i}</button>
                {else}
                    <button onclick="location.href='/pages/{$i}'">{$i}</button>
                {/if}
            {/for}
            {if $maxPages !== 1 && $currentPage !== $maxPages}
                <button id="next-page" class="arrow-btn" onclick="location.href='/pages/{$currentPage + 1}'">></button>
            {/if}
        </div>
    </div>
{/block}
