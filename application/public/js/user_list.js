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
  } else if (confirm(`会員 ID: ${id} を削除します。\n本当によろしいですか？`)) {
    showSpinner();
    const csrfToken = document.getElementById('csrf_token').value;

    response = await deleteUser(csrfToken, id);
    if (response['status_code'] === 200) {
      showMessage(response['message'], true);
      setTimeout(() => {
        window.location.reload();
      }, 3000);
    } else {
      hideSpinner();
      showMessage(response['message'], false);
    }
  } else {
    showMessage(`会員 ID: ${id} の削除をキャンセルしました。`, false);
  }
}

/**
 * 会員削除
 *
 * @async
 * @param {string} csrfToken
 * @param {number} id
 * @returns {Promise.<Array>}
 */
async function deleteUser(csrfToken, id) {
  try {
    const response = await fetch(`/api/user/${id}`, {
      method: 'DELETE',
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
