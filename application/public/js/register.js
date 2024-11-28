/**
 * 会員登録時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @param {?string} sendUrl
 */
async function onRegisterSubmit(event, sendUrl = null) {
  showSpinner();
  event.preventDefault();
  const { csrfToken, email, name, password, avatarImage, isAdmin } = getUpsertUserParameters();

  if (!csrfToken || !name || !email || !password) {
    hideSpinner();
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  success = false;
  response = await register(csrfToken, email, name, password, avatarImage, isAdmin);

  if (response['status_code'] === 200) {
    showMessage(response['message'], true);
    setTimeout(() => {
      if (sendUrl === null) {
        window.location.reload();
      } else {
        window.location.href = sendUrl;
      }
    }, 3000);
  } else {
    hideSpinner();
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
 * @param {string} avatar
 * @param {?boolean} isAdmin
 * @returns {Promise.<Array>}
 */
async function register(csrfToken, email, name, password, avatar, isAdmin) {
  try {
    const response = await fetch('/api/user', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
      },
      body: JSON.stringify({ email: email, name: name, password: password, avatar: avatar, is_admin: isAdmin })
    });
    return response.json();
  } catch (error) {
    return {
      'status_code': 500,
      'message': '何らかのエラーが発生しました。'
    };
  }
}
