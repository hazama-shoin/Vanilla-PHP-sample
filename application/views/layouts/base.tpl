<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name="title"}タイトル{/block}</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div id="message-banner" class="hidden"></div>
    <div id="loading-spinner" class="loading-spinner" style="display: none;">
        <div class="spinner"></div>
    </div>
    {block name="body"}{/block}
    <script src="/js/main.js"></script>
    {if isset($user)}
        <script src="/js/logged_in.js"></script>
    {/if}
    {block name="add-script"}{/block}
</body>
</html>
