document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('signup') && urlParams.get('signup') === 'success') {
        alert('Sign up successful! Please log in.');
    }
});
