var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    goodsLogo: null,
    domain: app.globalData.domain,
    detail: {},
  }, 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      resourceDomain:app.globalData.resourceDomain,
      id:options.id
    });
  },
  
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    that.setData({
      detail: {}
    });
    that.getDate();
  },
  //数据
  getDate: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var id = this.data.id;
    http.Post("weapp/orderservices/detail", { tokenId: tokenId, id:id }, function (res) {
      if (res.status == 1) {
        var { log, detail } = res.data;
        that.setData({
          detail,
          log
        })
      }
      wx.hideLoading();
    });
  },
  toHistory:function(){
    const _data = this.data.log;
    wx.navigateTo({
      url: '../os-history/os-history',
      success: function(res) {
        // 通过eventChannel向被打开页面传送数据
        res.eventChannel.emit('acceptDataFromOpenerPage', { data:_data })
      }
    })
  },
  toSend:function(){
    let id = this.data.id;
    wx.navigateTo({
      url: `../os-send/os-send?id=${id}`
    })
  }
})