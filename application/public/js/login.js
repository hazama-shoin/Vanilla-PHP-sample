/**
 * ログイン/新規会員登録の表示切り替え
 */
function toggleFormVisibility() {
  document.getElementById('login-form').classList.toggle('hidden');
  document.getElementById('regist-form').classList.toggle('hidden');
}

/**
 * ログイン処理時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @returns {Promise}
 */
async function onLoginSubmit(event) {
  showSpinner();
  event.preventDefault();

  const csrfToken = document.getElementById('csrf_token').value;
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  if (!csrfToken || !email || !password) {
    hideSpinner();
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  success = false;
  response = await login(csrfToken, email, password);
  if (response['status_code'] === 200) {
    window.location.href = '/';
  } else {
    hideSpinner();
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
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ email: email, password: password })
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
 * 会員登録メール送信処理時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @returns {Promise}
 */
async function onSendMailSubmit(event) {
  showSpinner();
  event.preventDefault();

  const csrfToken = document.getElementById('csrf_token').value;
  const name = document.getElementById('name').value;
  const email = document.getElementById('register_email').value;

  if (!csrfToken || !name || !email) {
    hideSpinner();
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  success = false;
  response = await sendMail(csrfToken, name, email);

  hideSpinner();
  showMessage(response['message'], (response['status_code'] === 200));
}

/**
 * 会員登録メール送信処理
 *
 * @async
 * @param {string} csrfToken
 * @param {string} name
 * @param {string} email
 * @returns {Promise.<Array>}
 */
async function sendMail(csrfToken, name, email) {
  try {
    const response = await fetch('/api/send_register_mail', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ name: name, email: email })
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}
