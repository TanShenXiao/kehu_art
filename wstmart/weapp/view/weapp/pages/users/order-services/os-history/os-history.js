var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    _data:[]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this;
    const eventChannel = this.getOpenerEventChannel();
    eventChannel.on('acceptDataFromOpenerPage', function({data}) {
      that.setData({_data:data});
    })

    this.setData({
      resourceDomain:app.globalData.resourceDomain,
    });
  }
})