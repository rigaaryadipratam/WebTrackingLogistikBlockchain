const LogisticsTracker = artifacts.require("LogisticsTracker");

module.exports = async function(callback) {
  try {
    const instance = await LogisticsTracker.deployed();

    // Ambil semua event ProductAdded
    const productEvents = await instance.getPastEvents("ProductAdded", {
      fromBlock: 0,
      toBlock: "latest"
    });

    // Ambil semua event LocationUpdated
    const locationEvents = await instance.getPastEvents("LocationUpdated", {
      fromBlock: 0,
      toBlock: "latest"
    });

    console.log("=== ProductAdded Events ===");
    productEvents.forEach((event, i) => {
      const { barcode, name, creator } = event.returnValues;
      console.log(`Event #${i + 1}`);
      console.log(`Barcode : ${barcode}`);
      console.log(`Name    : ${name}`);
      console.log(`Creator : ${creator}`);
      console.log('---------------------------');
    });

    console.log("\n=== LocationUpdated Events ===");
    locationEvents.forEach((event, i) => {
      const { barcode, location, timestamp } = event.returnValues;
      const time = new Date(timestamp * 1000).toLocaleString(); // konversi timestamp ke waktu lokal
      console.log(`Event #${i + 1}`);
      console.log(`Barcode   : ${barcode}`);
      console.log(`Location  : ${location}`);
      console.log(`Timestamp : ${time}`);
      console.log('---------------------------');
    });

    callback();
  } catch (err) {
    console.error("❌ Error querying events:", err);
    callback(err);
  }
};
