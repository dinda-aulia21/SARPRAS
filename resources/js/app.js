import './bootstrap';

document.querySelectorAll('[data-password-visibility]').forEach((button) => {
	button.addEventListener('click', () => {
		const input = document.getElementById(button.dataset.passwordVisibility);
		if (!input) return;

		const showPassword = input.type === 'password';
		input.type = showPassword ? 'text' : 'password';
		button.setAttribute('aria-label', `${showPassword ? 'Sembunyikan' : 'Tampilkan'} password`);
		button.setAttribute('aria-pressed', String(showPassword));
		button.querySelector('.password-eye-slash').hidden = showPassword;
	});
});
