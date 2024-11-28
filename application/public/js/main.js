/**
 * メッセージバナー表示処理
 *
 * @param {string} message
 * @param {boolean} isSuccess
 */
function showMessage(message, isSuccess = true) {
  const banner = document.getElementById('message-banner');
  if (banner === null) {
    return;
  }

  banner.textContent = message;
  banner.className = isSuccess ? 'success' : 'error';
  banner.style.display = 'block';

  // 一定時間後に非表示
  setTimeout(() => {
    banner.style.display = 'none';
    banner.className = 'hidden';
  }, 5000);
}

/**
 * スピナーを表示させる
 */
function showSpinner() {
  document.activeElement.blur();
  document.querySelector('body').inert = true;

  const spinner = document.getElementById('loading-spinner');
  spinner.style.display = 'flex';
}

/**
 * スピナーを消す
 */
function hideSpinner() {
  const spinner = document.getElementById('loading-spinner');
  spinner.style.display = 'none';

  document.querySelector('body').inert = false;
}
