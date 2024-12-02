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
  }, 3000);
}

/**
 * ログイン処理時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @returns {Promise}
 */
async function onLoginSubmit(event) {
  event.preventDefault();

  const csrfToken = document.getElementById('csrf_token').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  if (!csrfToken || !email || !password) {
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  const submitButton = document.getElementById('submit-button');
  submitButton.disabled = true;

  success = false;
  response = await login(csrfToken, email, password);
  if (response['status_code'] === 200) {
    window.location.href = '/';
  } else {
    submitButton.disabled = false;
    showMessage(response['message'], false);
  }
}

/**
 * ログイン処理
 *
 * @async
 * @param {string} csrfToken
 * @param {string} email
 * @param {string} password
 * @returns {Promise.<Array>}
 */
async function login(csrfToken, email, password) {
  try {
    const response = await fetch("/login", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ csrf_token: csrfToken, email: email, password: password })
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}

/**
 * 会員登録・更新時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @param {string} sendUrl
 * @param {boolean} isCreated
 */
async function onRegisterSubmit(event, sendUrl, isCreated = true) {
  event.preventDefault();

  const csrfToken = document.getElementById('csrf_token').value;
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  const validatedPassword = (!isCreated || password !== '');

  if (!csrfToken || !name || !email || !validatedPassword) {
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  const submitButton = document.getElementById('submit-button');
  submitButton.disabled = true;

  success = false;
  if (isCreated) {
    response = await register(csrfToken, email, name, password);
  } else {
    const id = document.getElementById('id').value;
    response = await updateUser(csrfToken, id, email, name, password);
  }

  if (response['status_code'] === 200) {
    showMessage(response['message'], true);
    setTimeout(() => {
      window.location.href = sendUrl;
    }, 3000);
  } else {
    submitButton.disabled = false;
    showMessage(response['message'], false);
  }
}

/**
 * 会員登録
 *
 * @async
 * @param {string} csrfToken
 * @param {string} email
 * @param {string} name
 * @param {string} password
 * @returns {Promise.<Array>}
 */
async function register(csrfToken, email, name, password) {
  try {
    const response = await fetch("/register", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ csrf_token: csrfToken, email: email, name: name, password: password })
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}

/**
 * 会員更新
 *
 * @async
 * @param {string} csrfToken
 * @param {number} id
 * @param {string} email
 * @param {string} name
 * @param {string} password
 * @returns {Promise.<Array>}
 */
async function updateUser(csrfToken, id, email, name, password) {
  try {
    const response = await fetch(`/update-user/${id}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ csrf_token: csrfToken, email: email, name: name, password: password })
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}

/**
 * 会員削除処理時のメッセージ表示
 *
 * @async
 * @param {number} id
 * @param {number} currentId
 */
async function onDeleteUserSubmit(id, currentId) {
  if (id === currentId) {
    showMessage('ログイン中の会員は削除できません。', false);
  } else if (confirm(`会員 ID: ${id} を削除しますか？`)) {
    disableButtons('btn', this);
    success = false;
    response = await deleteUser(id);
    if (response['status_code'] === 200) {
      success = true;
    }
    showMessage(response['message'], success);
    setTimeout(() => {
      window.location.href = '/';
    }, 3000);
  } else {
    showMessage(`会員 ID: ${id} の削除をキャンセルしました。`, false);
  }
}

/**
 * 会員削除
 *
 * @async
 * @param {number} id
 * @returns {Array}
 */
async function deleteUser(id) {
  try {
    const response = await fetch(`/delete-user/${id}`, {
      method: "GET"
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}

/**
 * 指定クラスに該当する全てのボタンを無効化
 *
 * @param {string} className
 */
function disableButtons(className) {
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
