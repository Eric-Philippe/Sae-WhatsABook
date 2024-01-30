const _API_URL = 'https://localhost:8008/api';

const API_URL = function (path: string) {
  return _API_URL + path;
};

export default API_URL;
