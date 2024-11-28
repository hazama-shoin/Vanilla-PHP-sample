/**
 * 会員登録・更新用パラメータ取得
 *
 * @returns Object
 */
function getUpsertUserParameters() {
  const csrfToken = document.getElementById('csrf_token').value;
  const email = document.getElementById('email').value;
  const name = document.getElementById('name').value;
  const password = document.getElementById('password').value;
  const avatar = document.getElementById('avatar');
  const avatarUrl = (avatar.files.length > 0) ? document.getElementById('avatar-preview').src : null;
  const avatarImage = (avatarUrl !== null) ? avatarUrl.replace(/data:.*\/.*;base64,/, '') : null;
  const isAdmin = document.getElementById('is_admin') ? document.getElementById('is_admin').checked : null;

  return { csrfToken, email, name, password, avatarImage, isAdmin };
}

/**
 * ファイルのサイズ（縦幅、横幅）を取得
 *
 * @param {Object} file
 * @returns {Promise}
 */
async function imagesize(file) {
  return new Promise((resolve, reject) => {
    const image = new Image();

    image.onload = () => {
      const size = {
        width: image.naturalWidth,
        height: image.naturalHeight,
      };

      URL.revokeObjectURL(image.src);
      resolve(size);
    };

    image.onerror = (error) => {
      reject(error);
    };

    image.src = URL.createObjectURL(file);
  });
}

addEventListener('load', function() {
  // アバター画像プレビュー処理
  const avatar = document.getElementById('avatar');
  const allowExtensions = '.(jpeg|jpg|png)$';
  if (avatar !== null) {
    avatar.addEventListener('change', async function (event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          // プレビュー画像を更新
          document.getElementById('avatar-preview').src = e.target.result;
        };
        if (file.size > 1000000) {
          showMessage('ファイルサイズが大きすぎます。アバターに使える画像は 1MBまでです。', false);
          avatar.value = '';
          return;
        }
        if (!file.name.match(allowExtensions)) {
          showMessage('アバターには拡張子が jpeg, jpg, png 以外のファイルは使用できません。', false);
          avatar.value = '';
          return;
        } else {
          const { width, height } = await imagesize(file);
          if (width > 500 || height > 500) {
            showMessage('画像の幅が大きすぎます。アバターに使える画像は縦横ともに 500pxまでです。', false);
            avatar.value = '';
            return;
          }
        }
        reader.readAsDataURL(file);
      }
    });
  }
});
