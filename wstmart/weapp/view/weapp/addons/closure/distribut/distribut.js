var http = require('../../../utils/request.js');
const app = getApp();
function distributInfo(page,data) {
  var tokenId = app.globalData.tokenId;
  http.Post("addon/distribut-distribut-weGetUser", { tokenId: tokenId }, function (res) {
    if (res.status == 1) {
      var distribut = res.data;
      page.setData({ distribut: distribut});
    }
  });
};
function distributConfig(data, cb) {
  var tokenId = app.globalData.tokenId;
  http.Post("addon/distribut-distribut-weDistributConfig", { tokenId: tokenId, shopId: data.shopId }, function (res) {
    if (res.status == 1) {
      typeof cb == "function" && cb({ distributConfig: res.data });
    }
  });
};

module.exports = {
  distributInfo: distributInfo,
  distributConfig: distributConfig
}