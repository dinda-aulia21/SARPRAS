import './bootstrap';

document.querySelectorAll('[data-password-visibility]').forEach((button) => {
	button.addEventListener('click', () => {
		const input = document.getElementById(button.dataset.passwordVisibility);
		if (!input) return;

		const showPassword = input.type === 'password';
		input.type = showPassword ? 'text' : 'password';
		button.setAttribute('aria-label', `${showPassword ? 'Sembunyikan' : 'Tampilkan'} password`);
		button.setAttribute('aria-pressed', String(showPassword));
		button.classList.toggle('is-visible', showPassword);
	});
});

document.querySelectorAll('.stat-grid .stat-card').forEach((card) => {
	card.addEventListener('pointerdown', () => {
		card.classList.remove('is-pressed');
		void card.offsetWidth;
		card.classList.add('is-pressed');
	});

	card.addEventListener('animationend', (event) => {
		if (event.animationName === 'stat-card-press') {
			card.classList.remove('is-pressed');
		}
	});

	if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
		card.addEventListener('pointermove', (event) => {
			const bounds = card.getBoundingClientRect();
			const horizontalPosition = (event.clientX - bounds.left) / bounds.width;
			const verticalPosition = (event.clientY - bounds.top) / bounds.height;

			card.style.setProperty('--stat-tilt-x', `${(0.5 - verticalPosition) * 12}deg`);
			card.style.setProperty('--stat-tilt-y', `${(horizontalPosition - 0.5) * 12}deg`);
		});

		card.addEventListener('pointerleave', () => {
			card.style.setProperty('--stat-tilt-x', '0deg');
			card.style.setProperty('--stat-tilt-y', '0deg');
		});
	}
});
