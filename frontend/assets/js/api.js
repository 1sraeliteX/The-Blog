const API_BASE = 'http://localhost:8000/api';

async function apiRequest(endpoint, options = {}) {
    const token = localStorage.getItem('token');
    const headers = {
        'Accept': 'application/json',
        ...(options.body && !(options.body instanceof FormData)
            ? { 'Content-Type': 'application/json' }
            : {}),
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...options.headers,
    };

    const response = await fetch(`${API_BASE}${endpoint}`, {
        ...options,
        headers,
        body: options.body instanceof FormData
            ? options.body
            : options.body
                ? JSON.stringify(options.body)
                : undefined,
    });

    if (response.status === 401 && endpoint.startsWith('/admin')) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/admin/dashboard.html';
        return;
    }

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || data.error || 'Request failed');
    }

    return data;
}

// Public API
function getPosts(page = 1) {
    return apiRequest(`/posts?page=${page}`);
}

function getPost(slug) {
    return apiRequest(`/posts/${slug}`);
}

// Admin API
function login(email, password) {
    return apiRequest('/admin/login', {
        method: 'POST',
        body: { email, password },
    });
}

function logout() {
    return apiRequest('/admin/logout', { method: 'POST' });
}

function getAdminPosts() {
    return apiRequest('/admin/posts');
}

function getAdminPost(id) {
    return apiRequest(`/admin/posts/${id}`);
}

function createPost(data) {
    return apiRequest('/admin/posts', {
        method: 'POST',
        body: data,
    });
}

function updatePost(id, data) {
    return apiRequest(`/admin/posts/${id}`, {
        method: 'PUT',
        body: data,
    });
}

function deletePost(id) {
    return apiRequest(`/admin/posts/${id}`, {
        method: 'DELETE',
    });
}

function togglePublish(id) {
    return apiRequest(`/admin/posts/${id}/toggle-publish`, {
        method: 'POST',
    });
}

function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    return apiRequest('/admin/upload-image', {
        method: 'POST',
        body: formData,
    });
}

// Public banner API
function getBanners() {
    return apiRequest('/banners');
}

// Admin banner API
function getAdminBanners() {
    return apiRequest('/admin/banners');
}

function createBanner(data) {
    return apiRequest('/admin/banners', {
        method: 'POST',
        body: data,
    });
}

function updateBanner(id, data) {
    return apiRequest(`/admin/banners/${id}`, {
        method: 'PUT',
        body: data,
    });
}

function deleteBanner(id) {
    return apiRequest(`/admin/banners/${id}`, {
        method: 'DELETE',
    });
}

// Public comment API
function getComments(slug) {
    return apiRequest(`/posts/${slug}/comments`);
}

function submitComment(slug, data) {
    return apiRequest(`/posts/${slug}/comments`, {
        method: 'POST',
        body: data,
    });
}

// Admin comment API
function getAdminComments() {
    return apiRequest('/admin/comments');
}

function approveComment(id) {
    return apiRequest(`/admin/comments/${id}/approve`, {
        method: 'PUT',
    });
}

function deleteComment(id) {
    return apiRequest(`/admin/comments/${id}`, {
        method: 'DELETE',
    });
}
