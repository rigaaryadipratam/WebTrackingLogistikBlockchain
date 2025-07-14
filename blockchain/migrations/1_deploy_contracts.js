// File: blockchain/migrations/1_deploy_contracts.js
const LogisticsTracker = artifacts.require("LogisticsTracker");

module.exports = function(deployer) {
  deployer.deploy(LogisticsTracker);
};