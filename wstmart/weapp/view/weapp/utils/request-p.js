var config = require('../config.js')
function Get(url, data) {
  return new Promise((resolve, reject) => {
    _Get(url, resolve, reject, data);
  });
};
function _Get(url, resolve, reject, data) {
  var header = { "content-type": "application/x-www-form-urlencoded" };
  if (!config.isFrist) {
    header.cookie = wx.getStorageSync("sessionid");
  }
  data.isWeapp = 1;
  wx.request({
    url: config.domain + url,
    data: data,
    header: header,
    method: "GET",
    success: function (res) {
      if (config.isFrist) {
        wx.removeStorageSync('sessionid');
        wx.setStorageSync("sessionid", res.header["Set-Cookie"]);
        config.isFrist = false;
      }
      var data = toJson(res.data);
      resolve(data);
    },
    fail: function (err) {
      reject();
    }
  })
};

function toJson(str, noAlert) {
  var json = {};
  try {
    if (typeof (str) == "object") {
      json = str;
    } else {
      wx.showToast({
        title: '系统发生错误，请联系管理员' + str,
        icon: 'none'
      });
    }
    if (typeof (noAlert) == 'undefined') {
      if (json.status && json.status == '-999') {
        wx.reLaunch({
          url: '/pages/login/login'
        })
        return false;
      }
    }
  } catch (e) {
    wx.showToast({
      title: '系统发生错误，请联系管理员' + str,
      icon: 'none'
    });
    json = {};
  }
  return json;
};

module.exports = {
  Get: Get
};