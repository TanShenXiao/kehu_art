
var http = require('../../../../../utils/request.js');
const app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    duser:[],
    dmoneys: [],
    page: 0,
    lastPage: 0,
    isActive: 1,
    type: 2
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
  },
  onShow: function () {
    this.distributUser();
    this.moneyList();
  },
  inChoice: function (e) {
    var that = this;
    var type = e.currentTarget.dataset.type;
    var isActive = 1;
    if (type == 1) {
      isActive = 0;
    }
    that.setData({
      dmoneys: [],
      isActive: isActive,
      type: type,
      page: 0,
      lastPage: 0
    });
    this.moneyList();
  },
  moneyList: function () {
    var that = this;
    var page = that.data.page;
    var type = that.data.type;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post('addon/distribut-distribut-weQueryDistributMoneys', { tokenId: tokenId, page: page, type: type }, function (res) {
      if (res.status == 1) {
        var dmoneys = that.data.dmoneys;
        dmoneys = dmoneys.concat(res.data);

        that.setData({
          dmoneys: dmoneys,
          page: page,
          lastPage: res.last_page
        })
      }
      wx.hideLoading();
    })
  },
  distributUser: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/distribut-distribut-weGetUser", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          duser: res.data
        })
      }
    });
  },
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.moneyList();
    }
  }
})