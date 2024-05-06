const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');
const signUpForm = document.querySelector('.form-container.sign-up');
const signInForm = document.querySelector('.form-container.sign-in');
const toggleLeftPanel = document.querySelector('.toggle-left');
const toggleRightPanel = document.querySelector('.toggle-right');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
    toggleLeftPanel.style.transform = 'translateX(0)';
    toggleRightPanel.style.transform = 'translateX(100%)';
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
    toggleLeftPanel.style.transform = 'translateX(-200%)';
    toggleRightPanel.style.transform = 'translateX(0)';
});
