/**
 * ログアウト時の確認メッセージ表示
 *
 * @async
 */
async function onLogoutSubmit() {
  if (confirm('ログアウトします。よろしいですか？')) {
    showSpinner();
    const csrfToken = document.getElementById('csrf_token').value;
    success = false;
    response = await logout(csrfToken);
    if (response['status_code'] === 200) {
      window.location.href = '/';
    } else {
      hideSpinner();
      showMessage(response['message'], false);
    }
  }
}

/**
 * ログアウト処理
 *
 * @async
 * @param {string} csrfToken
 * @returns {Promise.<Array>}
 */
async function logout(csrfToken) {
  try {
    const response = await fetch('/api/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-Token': csrfToken
      },
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}

window.addEventListener('load', function() {
  // ハンバーガーメニューの開閉制御
  const menuToggle = document.querySelector('.menu-toggle');
  const dropdownMenu = document.querySelector('.dropdown-menu');
  if (menuToggle !== null && dropdownMenu !== null) {
    menuToggle.addEventListener('click', () => {
      const isDisplayed = dropdownMenu.style.display === 'block';
      dropdownMenu.style.display = isDisplayed ? 'none' : 'block';
    });

    // メニュー以外のクリックで閉じる
    document.addEventListener('click', (event) => {
      if (!menuToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = 'none';
      }
    });
  }

  // 検索バーの表示・非表示
  const searchIcon = document.getElementById('search-icon');
  const searchBar = document.getElementById('search-bar');
  if (searchIcon !== null && searchBar !== null) {
    searchIcon.addEventListener('click', () => {
      const isDisplayed = searchBar.style.display === 'inline-flex';
      searchBar.style.display = isDisplayed ? 'none' : 'inline-flex';
    });
  }
});
