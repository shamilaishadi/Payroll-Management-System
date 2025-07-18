import React, { useState } from 'react';
import './PageStyles.css';

export default function AttendanceForm() {
  const [tab, setTab] = useState('email');
  const [value, setValue] = useState('');
  const [pin, setPin] = useState('');
  const [message, setMessage] = useState('');
  const [receipt, setReceipt] = useState(null);
  const [timeoutId, setTimeoutId] = useState(null);
  const [countdown, setCountdown] = useState(30);
  const [intervalId, setIntervalId] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();

    // Simple frontend validation
    if (!value || !pin) {
      setMessage(' Email/ID and PIN are required');
      return;
    }

    try {
      const auth_type = tab === 'email' ? 'email' : 'employee_id';
      setMessage(''); // Clear previous messages
      const body = {
        auth_type,
        pin,
        ...(auth_type === 'email'
          ? { email: value }
          : { employee_id: parseInt(value, 10) }),
      };

      const res = await fetch(`${process.env.REACT_APP_BACKEND_URL}/api/attendance/mark`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
        body: JSON.stringify(body),
      });

      const data = await res.json();

      if (!res.ok) {
        // Show custom error if present, otherwise show general error
        const customError = data.error || data.message;
        throw new Error(customError || 'Failed to mark attendance');
      }

      // Success: show receipt
      setReceipt(data);
      // setMessage('✅ Attendance marked successfully.');
      setCountdown(30);
      const id = setTimeout(() => {
        handleCloseReceipt();
      }, 30000);
      setTimeoutId(id);

      // Start countdown interval
      const intervalId = setInterval(() => {
        setCountdown(prev => {
          if (prev <= 1) {
            clearInterval(intervalId);
            return 0;
          }
          return prev - 1;
        });
      }, 1000);

      // Store intervalId for cleanup
      setIntervalId(intervalId);
    } catch (err) {
      console.error('Error:', err);
      setMessage(err.message);
      setTimeout(() => setMessage(''), 30000);
    }
  };

  // Store intervalId for cleanup
  const handleCloseReceipt = () => {
    setReceipt(null);
    setMessage('');
    setValue('');
    setPin('');
    setCountdown(30);
    if (timeoutId) clearTimeout(timeoutId);
    if (intervalId) clearInterval(intervalId);
  };

  const renderReceipt = () => {
    if (!receipt) return null;

    const { status, user, timestamp } = receipt;
    const isSignIn = status === 'signed_in';
    const isSignOut = status === 'signed_out';

    return (
      <div className="receipt">
        <h3>🎉 Attendance Receipt</h3>
        <p><strong>User:</strong> {user}</p>
        <p><strong>Status:</strong> {status}</p>
        <p><strong>{isSignIn ? 'Sign In Status' : 'Sign Out Status'}:</strong> {receipt.sign_in_status || receipt.sign_out_status}</p>
        <p><strong>Timestamp:</strong> {new Date(timestamp).toLocaleString()}</p>

        {isSignOut && (
          <>
            <p><strong>Worked Minutes:</strong> {receipt.worked_minutes.toFixed(2)}</p>
            <p><strong>Overtime Minutes:</strong> {receipt.overtime_minutes.toFixed(2)}</p>
          </>
        )}

        <button className="close-btn" onClick={handleCloseReceipt}>Close</button>
        <p className="auto-close">This receipt will auto-close in {countdown} second{countdown !== 1 ? 's' : ''}.</p>
      </div>
    );
  };

  return (
    <div className="container">
      <h2>Mark Attendance</h2>

      {!receipt ? (
        <form onSubmit={handleSubmit}>
          <div className="tabs">
            <button
              type="button"
              onClick={() => {
                setTab('email');
                setValue('');
              }}
              className={tab === 'email' ? 'active' : ''}
            >
              Email
            </button>
            <button
              type="button"
              onClick={() => {
                setTab('id');
                setValue('');
              }}
              className={tab === 'id' ? 'active' : ''}
            >
              Employee ID
            </button>
          </div>

          {tab === 'email' ? (
            <input
              type="email"
              placeholder="Enter Email"
              value={value}
              onChange={(e) => setValue(e.target.value)}
              required
            />
          ) : (
            <input
              type="number"
              placeholder="Enter Employee ID"
              value={value}
              onChange={(e) => setValue(e.target.value)}
              required
            />
          )}

          <input
            type="password"
            placeholder="Enter PIN"
            value={pin}
            onChange={(e) => setPin(e.target.value)}
            required
          />

          <button type="submit">Submit</button>
        </form>
      ) : (
        renderReceipt()
      )}
  
      {message && (
        <div className="error-container" style={{ marginTop: '20px', textAlign: 'center', color: 'red' , backgroundColor: '#f8d7da',padding:'6px', borderRadius: '5px' }}>
          <p style={{ margin: 0, padding:"0" }}>
             {message}</p>
        </div>
      )}
    </div>
  );
}
