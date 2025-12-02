const AuthService = require('../services/AuthService');

async function login(req, res) {
  const { email, password } = req.body;
  if (!email || !password) {
    return res.status(400).json({ message: 'Email and password are required' });
  }
  try {
    const session = await AuthService.login(email, password);
    if (!session) return res.status(401).json({ message: 'Invalid credentials' });
    res.json({ token: session.token, user: { id: session.user.id, email: session.user.email, name: session.user.name } });
  } catch (err) {
    res.status(500).json({ message: 'Login failed', error: err.message });
  }
}

module.exports = { login };
