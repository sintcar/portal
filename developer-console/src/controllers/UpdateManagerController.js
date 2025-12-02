const UpdateManagerService = require('../services/UpdateManagerService');

async function manifest(req, res) {
  const channel = req.query.channel || 'stable';
  try {
    const data = await UpdateManagerService.manifest(channel);
    if (!data) return res.status(404).json({ message: 'No release for channel' });
    res.json(data);
  } catch (err) {
    res.status(500).json({ message: 'Unable to fetch manifest', error: err.message });
  }
}

async function report(req, res) {
  const { jobId, status, completedAt } = req.body;
  if (!jobId || !status) return res.status(400).json({ message: 'jobId and status are required' });
  try {
    const updated = await UpdateManagerService.reportJobStatus({ jobId, status, completedAt });
    if (!updated) return res.status(404).json({ message: 'Job not found' });
    res.json({ ok: true });
  } catch (err) {
    res.status(500).json({ message: 'Unable to update job status', error: err.message });
  }
}

async function list(req, res) {
  try {
    const jobs = await UpdateManagerService.listJobs();
    res.json({ data: jobs });
  } catch (err) {
    res.status(500).json({ message: 'Unable to fetch update jobs', error: err.message });
  }
}

module.exports = { manifest, report, list };
