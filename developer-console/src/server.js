const express = require('express');
const bodyParser = require('body-parser');
const router = require('./routes');
const { getDb } = require('./config/database');
const { ensureSeedAdmin } = require('./services/AuthService');

const app = express();
const port = process.env.PORT || 4002;

app.use(bodyParser.json());
app.use('/api/devconsole', router);

app.get('/health', (req, res) => res.json({ status: 'ok' }));

getDb();
ensureSeedAdmin();

app.listen(port, () => {
  console.log(`Developer Console API listening on port ${port}`);
});

module.exports = app;
