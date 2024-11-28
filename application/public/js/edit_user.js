/**
 * 会員更新時のメッセージ表示
 *
 * @async
 * @param {document#event:submit} event
 * @param {string} sendUrl
 * @param {boolean} isCreated
 */
async function onUpdateSubmit(event, sendUrl) {
  showSpinner();
  event.preventDefault();

  const id = document.getElementById('id').value;
  const { csrfToken, email, name, password, avatarImage, isAdmin } = getUpsertUserParameters();

  if (!csrfToken || !id || !name || !email) {
    hideSpinner();
    showMessage('入力エラー: 全ての項目を正しく入力してください。', false);
    return;
  }

  success = false;
  response = await updateUser(csrfToken, id, email, name, password, avatarImage, isAdmin);

  if (response['status_code'] === 200) {
    showMessage(response['message'], true);
    setTimeout(() => {
      window.location.reload();
    }, 3000);
  } else {
    hideSpinner();
    showMessage(response['message'], false);
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
 * @param {string} avatar
 * @param {?boolean} isAdmin
 * @returns {Promise.<Array>}
 */
async function updateUser(csrfToken, id, email, name, password, avatar, isAdmin) {
  try {
    const response = await fetch(`/api/user/${id}`, {
      method: 'PUT',
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
