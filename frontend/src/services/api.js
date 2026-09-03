import axios from 'axios';

// Create instance Axios dengan konfigurasi dasar
const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api', // Alamat URL Backend Laravel
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Interceptor Request: Otomatis menempelkan Bearer Token di setiap request
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('access_token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor Response: Otomatis tangani error global (misal: Token Expired / Unauthenticated)
api.interceptors.response.use(
  (response) => response,
  (error) => {
    // Jika backend mengembalikan 401 (Unauthenticated / Session Expired)
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('access_token');
      localStorage.removeItem('user_role');
      localStorage.removeItem('user_data');
      
      // Tendang ke halaman login jika tidak sedang di halaman login/register
      if (window.location.pathname !== '/login' && window.location.pathname !== '/register') {
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

export default api;