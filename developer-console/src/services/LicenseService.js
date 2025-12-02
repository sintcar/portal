const { v4: uuidv4 } = require('uuid');
const License = require('../models/License');

async function issueLicense({ customer, expiresAt, notes, issuedBy, licenseKey }) {
  const id = uuidv4();
  const now = new Date().toISOString();
  const generatedKey = licenseKey || `LIC-${uuidv4()}`;
  return License.create({
    id,
    licenseKey: generatedKey,
    customer,
    expiresAt,
    status: 'active',
    notes,
    issuedBy,
    createdAt: now,
  });
}

async function listLicenses() {
  return License.all();
}

module.exports = { issueLicense, listLicenses };
