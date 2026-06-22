function getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

async function handleLogout() {
    try {
        await logout();
    } catch (e) {
        // ignore
    }
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/admin/dashboard.html';
}
