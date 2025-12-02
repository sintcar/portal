const LicenseService = require('../services/LicenseService');

async function issue(req, res) {
  const { customer, expiresAt, notes, licenseKey } = req.body;
  const issuedBy = req.user?.id;
  if (!customer) return res.status(400).json({ message: 'Customer is required' });
  try {
    const license = await LicenseService.issueLicense({ customer, expiresAt, notes, issuedBy, licenseKey });
    res.status(201).json(license);
  } catch (err) {
    res.status(500).json({ message: 'Unable to issue license', error: err.message });
  }
}

async function list(req, res) {
  try {
    const licenses = await LicenseService.listLicenses();
    res.json({ data: licenses });
  } catch (err) {
    res.status(500).json({ message: 'Unable to fetch licenses', error: err.message });
  }
}

module.exports = { issue, list };
