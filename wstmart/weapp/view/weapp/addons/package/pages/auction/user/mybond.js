var http = require('../../../../../utils/request.js');
var app = getApp();
Page({
  data: {
    resourceDomain: app.globalData.resourceDomain,
    domain: app.globalData.domain,//域名
    catId:0,
    goods: [],//商品
    isLoad:true,
    nowTime: '',
    page: 0,
    lastPage: 0,
  },
  onLoad: function (options) {
  },
  onShow: function (options) {
    app.editTabBar();
    this.goodsList();
  },
  //上拉加载
  onReachBottom: function () {
    var lastPage = this.data.lastPage;
    var page = this.data.page;
    if (page <= lastPage) {
      this.goodsList();
    }
  },
  toDetail:function({ currentTarget: { dataset: { id } } }){
    wx.navigateTo({
        url: `/addons/package/pages/auction/goods/detail?id=${id}`
    })
  },
  //商品
  goodsList: function () {
    var that = this;
    var params = {};
    var page = that.data.page + 1;
    params.tokenId = app.globalData.tokenId;
    params.page = page;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/auction-Weapps-pageQueryByMoney", params, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        var goods = that.data.goods;
        if(res.data.data.length > 0){
          goods = goods.concat(res.data.data);
          that.setData({
            goods: goods,
            page: page,
            lastPage: res.data.last_page,
            isLoad:false,
          });
        }
      }else{
        app.prompt(res.msg);
      }
    });
  }
});