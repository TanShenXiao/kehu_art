var config = require('../config.js')
function Get(url, data, cb) {
  data.isWeapp=1;
  var shareUserId = wx.getStorageSync('shareUserId');
  if (shareUserId>0){
    data.shareUserId = shareUserId;
  }
  var header = { "content-type": "application/x-www-form-urlencoded" };
  if (!config.isFrist) {
    header.cookie = wx.getStorageSync("sessionid");
  }

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
      typeof cb == "function" && cb(data);
    },
    fail: function (err) {
      typeof cb == "function" && cb(err);
    }
  })
};
function Post(url, data, cb) {
  data.isWeapp = 1;
  var shareUserId = wx.getStorageSync('shareUserId');
  if (shareUserId > 0) {
    data.shareUserId = shareUserId;
  }
  var header = { "content-type": "application/x-www-form-urlencoded" };
  if (!config.isFrist) {
    header.cookie = wx.getStorageSync("sessionid");
  }
  wx.request({
    url: config.domain + url,
    data: data,
    header: header,
    method: "POST",
    success: function (res) {
      if (config.isFrist) {
        wx.removeStorageSync('sessionid');
        wx.setStorageSync("sessionid", res.header["Set-Cookie"]);
        config.isFrist = false;
      }
      var data = toJson(res.data);
      typeof cb == "function" && cb(data);
    },
    fail: function (err) {
      typeof cb == "function" && cb(err);
    }
  })
};
function Upload(url, file, data, cb) {
  wx.uploadFile({
    url: config.domain + url,
    filePath: file,
    name: "file",
    formData: data,
    success: (res) => {
      if (typeof (res.data) == "string") {
        typeof cb == "function" && cb(JSON.parse(res.data), "");
      } else {
        typeof cb == "function" && cb(res.data, "");
      }
    },
    fail: (err) => {
      typeof cb == "function" && cb(null, err.errMsg);
    }
  });
};

function toJson(str, noAlert) {
  var json = {};
  try {
    if (typeof (str) == "object") {
      json = str;
    } else {
      json = eval("(" + str + ")");
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
    wx.showModal({
      title: '提示',
      content: '系统发生错误:' + e.getMessage,
      showCancel: false,
      confirmText: "确定"
    })
    json = {};
  }
  return json;
};

module.exports = {
  Get: Get,
  Post: Post,
  Upload: Upload
};