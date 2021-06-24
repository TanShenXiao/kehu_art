var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
      orderId:0,
      resourceDomain:app.globalData.resourceDomain,
      expressData:'',
      currentIndex: 0
  },
  /**
 * 生命周期函数--监听页面加载
 */
  onLoad: function (options) {
    var that = this;
    that.setData({ orderId: options.orderId})
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    wx.showLoading();
    http.Post("addon/kuaidi-weapp-checkExpress", { tokenId: tokenId, orderId: that.data.orderId}, function (res) {
      if (res.status == 1) {
          that.setData({ 
            data: res.data.data,
            expressData:res.data.data.expressData,
            resourceDomain:app.globalData.resourceDomain
          });
          wx.hideLoading();
      } 
    });
  },
  //点击切换，滑块index赋值
  checkCurrent: function (e) {
    const that = this;

    if (that.data.currentIndex === e.target.dataset.current) {
      return false;
    } else {

      that.setData({
        currentIndex: e.target.dataset.current
      })
    }
  }
})