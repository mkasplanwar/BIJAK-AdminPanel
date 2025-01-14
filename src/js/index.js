document.addEventListener('DOMContentLoaded', () => {
    const eyeIcon = document.querySelector('.eye-icon');
    const passwordInput = document.querySelector('#password');
    const loginForm = document.querySelector('#loginForm');

    eyeIcon.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeIcon.style.opacity = type === 'password' ? '1' : '0.7';
    });

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        // Add your login logic here
        console.log('Login attempted');
    });
});