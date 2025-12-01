document.addEventListener('DOMContentLoaded', () => {
    const loginCard = document.getElementById('auth-login');
    const registerCard = document.getElementById('auth-register');

    const show = (mode) => {
        if (!loginCard || !registerCard) return;
        if (mode === 'register') {
            registerCard.style.display = 'block';
            loginCard.style.display = 'none';
        } else {
            registerCard.style.display = 'none';
            loginCard.style.display = 'block';
        }
    };

    document.querySelectorAll('[data-show="register"]').forEach((btn) => {
        btn.addEventListener('click', () => show('register'));
    });

    document.querySelectorAll('[data-show="login"]').forEach((btn) => {
        btn.addEventListener('click', () => show('login'));
    });

    // initial state comes from data-mode on the shell
    const shell = document.querySelector('.auth-shell');
    if (shell) {
        show(shell.dataset.mode || 'login');
    }

    const profileInput = document.getElementById('profile_image');
    const preview = document.getElementById('image-preview');
    if (profileInput && preview) {
        profileInput.addEventListener('change', () => {
            const file = profileInput.files?.[0];
            if (!file) {
                preview.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target?.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }
});
