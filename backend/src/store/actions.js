import axiosClient from "../axios";
import { PRODUCTS_PER_PAGE } from "../constants.js";

/**
 * ------------------------
 * AUTH
 * ------------------------
 */

export function getCurrentUser({ commit }, data) {
    return axiosClient.get('/user', data)
        .then(({ data }) => {
            commit('setUser', data);
            return data;
        });
}

export function login({ commit }, data) {
    return axiosClient.post('/login', data)
        .then(({ data }) => {
            commit('setUser', data.user);
            commit('setToken', data.token);
            return data;
        });
}

export function logout({ commit }) {
    return axiosClient.post('/logout')
        .then(response => {
            commit('setToken', null);
            return response;
        });
}

/**
 * ------------------------
 * PRODUCTS
 * ------------------------
 */

export function getProducts(
    { commit },
    {
        url = null,
        search = '',
        per_page = PRODUCTS_PER_PAGE,
        sort_field,
        sort_direction
    } = {}
) {
    commit('setProducts', [true]);
    url = url || 'products';

    return axiosClient.get(url, {
        params: { search, per_page, sort_field, sort_direction }
    })
        .then(res => {
            commit('setProducts', [false, res.data]);
        })
        .catch(() => {
            commit('setProducts', [false]);
        });
}

export function getProduct({ commit }, id) {
    return axiosClient.get(`/products/${id}`);
}

/**
 * Create product
 * Fully compatible with ProductRequest
 */
export function createProduct({ commit }, product) {
    const form = new FormData();

    form.append('title', product.title);
    form.append('price', product.price);
    form.append('description', product.description || '');
    form.append('published', product.published ? 1 : 0);

    // Append categories
    if (Array.isArray(product.categories)) {
        product.categories.forEach(categoryId => {
            form.append('categories[]', categoryId);
        });
    }

    // Append images (ONLY File objects)
    if (Array.isArray(product.images)) {
        product.images.forEach(file => {
            if (file instanceof File) {
                form.append('images[]', file);
            }
        });
    }

    return axiosClient.post('/products', form);
}

/**
 * Update product
 * Correct handling of images, categories and deleted images
 */
export function updateProduct({ commit }, product) {
    const form = new FormData();

    form.append('id', product.id);
    form.append('title', product.title);
    form.append('price', product.price);
    form.append('description', product.description || '');
    form.append('published', product.published ? 1 : 0);

    // Append categories
    if (Array.isArray(product.categories)) {
        product.categories.forEach(categoryId => {
            form.append('categories[]', categoryId);
        });
    }

    // Append NEW images only (File objects)
    if (Array.isArray(product.images)) {
        product.images.forEach(file => {
            if (file instanceof File) {
                form.append('images[]', file);
            }
        });
    }

    // Append deleted images
    if (Array.isArray(product.deleted_images)) {
        product.deleted_images.forEach(imageId => {
            form.append('deleted_images[]', imageId);
        });
    }

    // Append image positions
    if (product.image_positions && typeof product.image_positions === 'object') {
        Object.keys(product.image_positions).forEach(imageId => {
            form.append(`image_positions[${imageId}]`, product.image_positions[imageId]);
        });
    }

    form.append('_method', 'PUT');

    return axiosClient.post(`/products/${product.id}`, form);
}

export function deleteProduct({ commit }, id) {
    return axiosClient.delete(`/products/${id}`);
}

/**
 * ------------------------
 * CATEGORIES
 * ------------------------
 */

export function getCategories({ commit }, { sort_field, sort_direction } = {}) {
    commit('setCategories', [true]);

    return axiosClient.get('/categories', {
        params: { sort_field, sort_direction }
    })
        .then(response => {
            commit('setCategories', [false, response.data]);
        })
        .catch(() => {
            commit('setCategories', [false]);
        });
}

export function createCategory({ commit }, category) {
    return axiosClient.post('/categories', category);
}

export function updateCategory({ commit }, category) {
    return axiosClient.put(`/categories/${category.id}`, category);
}

export function deleteCategory({ commit }, id) {
    return axiosClient.delete(`/categories/${id}`);
}
