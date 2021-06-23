var http = require('../../../../../utils/request.js');
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,//域名
    goods:[],
    currPage:0,
    totalPage:0,
    moreText:'加载中',
    totalCnt:-1
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getData();
  },

  getData(){
    var that = this;
    var params = {};
    params.tokenId = app.globalData.tokenId;
    params.pagesize = 10;
    params.page = parseInt(that.data.currPage)+1;
    wx.showLoading({ title: '加载中' });
    http.Get("addon/bargain-users-wePageQuery", params , function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        that.data.goods.push.apply(that.data.goods, res.data.data);  
        that.setData({
          goods:that.data.goods,
          currPage:res.data.current_page,
          totalPage:res.data.last_page,
          totalCnt:res.data.total
        });
      }
    });
  },
  viewGoodsDetail: function (e) {
    var bargainId = e.currentTarget.dataset.bargainid;
    wx.navigateTo({
      url: '../goods/detail?id=' + bargainId
    });
  },
  onScrollToLower(e) {
    var currPage = parseInt(this.data.currPage);
    var totalPage = parseInt(this.data.totalPage);
    if (currPage + 1 > totalPage) {
      this.setData({
        moreText: '加载完啦'
      });
      return;
    } else {
      this.getData();
    }
  }
})