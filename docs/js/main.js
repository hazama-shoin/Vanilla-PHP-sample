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

// アバター画像プレビュー処理
const avatar = document.getElementById('avatar');
if (avatar !== null) {
  avatar.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('avatar-preview').src = e.target.result; // プレビュー画像を更新
      };
      reader.readAsDataURL(file);
    }
  });
}

// メッセージバナー表示処理
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
  }, 3000);
}

// 会員登録・更新時のメッセージ表示
function onRegisterSubmit(event, sendUrl, message = "会員登録が完了しました。", isCreated = true) {
  event.preventDefault();

  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const password = isCreated ? document.getElementById('password').value : true;

  if (name && email && password) {
    showMessage(message, true);
    setTimeout(() => {
      window.location.href = sendUrl;
    }, 3000);
  } else {
    showMessage("入力エラー: 全ての項目を正しく入力してください。", false);
  }
}

// 会員削除処理
function deleteUser(id) {
  if (confirm(`会員 ID: ${id} を削除しますか？`)) {
    disableButtons('btn', this);
    showMessage(`会員 ID: ${id} を削除しました。`, true);
    setTimeout(() => {
      window.location.href = '';
    }, 3000);
  } else {
    showMessage(`会員 ID: ${id} の削除をキャンセルしました。`, false);
  }
}

// 指定クラスに該当する全てのボタンを無効化
function disableButtons(className, clickFnc = null) {
  const targets = document.getElementsByClassName(className);

  // aタグの無効化
  const links = Array.prototype.filter.call(targets, function (target) {
		return target.nodeName === 'A';
  });
  if (links !== null && links.length > 0) {
    for (let i = 0; i < links.length; i++) {
      links[i].href = 'javascript:void(0)';
      links[i].tabIndex = -1;
      links[i].onclick = function () { return false; };
    }
  }

  // buttonタグの無効化
  const buttons = Array.prototype.filter.call(targets, function (target) {
		return target.nodeName === "BUTTON"; // aタグの意。DIVとかもある。この場合は、classNameに一致するaタグという意。
  });
  if (buttons !== null && buttons.length > 0) {
    for (let i = 0; i < buttons.length; i++) {
      buttons[i].disabled = true;
    }
  }
}
