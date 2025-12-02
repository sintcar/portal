const InstanceService = require('../services/InstanceService');
const UpdateManagerService = require('../services/UpdateManagerService');

async function register(req, res) {
  const { name, licenseId, currentVersion, channel, lastSeenAt } = req.body;
  if (!name) return res.status(400).json({ message: 'Instance name is required' });
  try {
    const instance = await InstanceService.registerInstance({ name, licenseId, currentVersion, channel, lastSeenAt });
    res.status(201).json(instance);
  } catch (err) {
    res.status(500).json({ message: 'Unable to register instance', error: err.message });
  }
}

async function list(req, res) {
  try {
    const instances = await InstanceService.listInstances();
    res.json({ data: instances });
  } catch (err) {
    res.status(500).json({ message: 'Unable to fetch instances', error: err.message });
  }
}

async function triggerUpdate(req, res) {
  const { id } = req.params;
  const { releaseId, strategy } = req.body;
  if (!releaseId) return res.status(400).json({ message: 'releaseId is required to schedule update' });
  try {
    const job = await UpdateManagerService.createUpdateJob({ instanceId: id, releaseId, strategy });
    res.status(201).json(job);
  } catch (err) {
    res.status(500).json({ message: 'Unable to create update job', error: err.message });
  }
}

module.exports = { register, list, triggerUpdate };
