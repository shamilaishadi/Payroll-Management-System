import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import './PageStyles.css';

export default function LoginPage() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();
  const isFormValid = username.trim() && password;

  const handleLogin = async () => {
    setError('');
    try {
      const res = await fetch(`${process.env.REACT_APP_BACKEND_URL}/api/attendance-admin/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      });
      let data;
      try {
        data = await res.json();
      } catch (e) {
        setError('Invalid server response.');
        return;
      }
      if (!res.ok) {
        setError(data.error || data.message || 'Login failed');
        return;
      }
      localStorage.setItem('token', data.token);
      navigate('/attendance');
    } catch (err) {
      setError(err.message || 'Network error');
    }
  };

  return (
    <div className="container">
      <h2>Admin Login</h2>
      {error && <div className="alert alert-danger">{error}</div>}
      <input type="text" placeholder="Username" value={username} onChange={e => setUsername(e.target.value)} />
      <input type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} />
      <button onClick={handleLogin} disabled={!isFormValid}>Login</button>
    </div>
  );
}
