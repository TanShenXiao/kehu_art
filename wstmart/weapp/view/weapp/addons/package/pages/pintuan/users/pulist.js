var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    resourceDomain: app.globalData.resourceDomain,//资源路径
    ftype:0,
    page: 0,
    lastPage: 0,
    totalCnt: -1,
    glist:[],
    userPhoto: app.globalData.resourceDomain + app.globalData.confInfo.userLogo,
    userName:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    this.getUserInfo();
    this.getList();
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.getList();
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  },
  changeTab:function(e){
    var that = this;
    that.setData({
      ftype: e.currentTarget.dataset.type,
      totalCnt:-1,
      page: 0,
      lastPage: 0,
      glist: []
    });
    that.getList();
  },
  //列表
  getUserInfo: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/pintuan-pintuan-weUserInfo", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          userName: res.data.userName,
          userPhoto: app.userPhoto(res.data.userPhoto)
        });
      }
    });
  },
  //列表
  getList: function () {
    var that = this;
    var page = that.data.page;
    var ftype = that.data.ftype;
    var tokenId = app.globalData.tokenId;
    if (page == 0) wx.showLoading({ title: '加载中' });
    page = page + 1;
    http.Post("addon/pintuan-pintuan-wePageQuery", { tokenId: tokenId, ftype: ftype, page: page, pagesize:10 },       function (res) {
      if (res.status == 1) {
        var glist = that.data.glist;
        glist = glist.concat(res.data);
        that.setData({
          glist: glist,
          page: page,
          totalCnt: res.total,
          lastPage: res.last_page
        })
      }
      wx.hideLoading();
    });
  },
  toDetail:function(e){
    var tuanno = e.currentTarget.dataset.tuanno;
    wx.navigateTo({
      url: 'tuan_detail?tuanNo=' + tuanno+"&vtype=1"
    });
  },
  toCancel: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var tokenId = app.globalData.tokenId;
    wx.showModal({
      title: '提示',
      content: '您确定要取消该拼团吗？',
      success: function (res) {
        if (res.confirm) {
          http.Post("addon/pintuan-pintuan-weCancel", { tokenId: tokenId, id: id }, function (res) {
            if (res.status == 1) {
              that.setData({
                glist: [],
                page: 0
              });
              that.getList();
            } else {
              app.prompt(res.msg);
            }
          });
        }
      }
    })
  },
  choicePay:function(e){
    var pkey = e.currentTarget.dataset.pkey;
    wx.navigateTo({
      url: '/addons/package/pages/pintuan/payment/payment?pkey=' + pkey
    });
  }
})