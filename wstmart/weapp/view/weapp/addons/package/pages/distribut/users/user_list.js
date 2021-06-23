var http = require('../../../../../utils/request.js');
const app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    dusers: [],
    page: 0,
    lastPage: 0
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },
  onShow: function () {
    this.userList();
  },
  userList: function () {
    var that = this;
    var page = that.data.page;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post('addon/distribut-distribut-weQueryDistributUsers', { tokenId: tokenId, page: page}, function (res) {
      if (res.status == 1) {
        var dusers = that.data.dusers;
        dusers = dusers.concat(res.data);
        for (let i = 0; i < dusers.length; i++) {
          dusers[i].userPhoto = app.userPhoto(dusers[i].userPhoto2);

        }
        that.setData({
          dusers: dusers,
          page: page,
          lastPage: res.last_page
        })
      }
      wx.hideLoading();
    })
  },
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.userList();
    }
  }
})